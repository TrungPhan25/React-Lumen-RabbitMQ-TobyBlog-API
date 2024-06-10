<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function sendComment(CommentRequest $request)
    {
        try {
            $data         = $request->all();
            $postSlug     = $data['postSlug'];
            $comment_data = $data['comment'];
            $post         = Post::where('slug', $postSlug)->first();

            // Save data comment
            $comment          = new Comment();
            $comment->name    = $data['name'];
            $comment->email   = $data['email'];
            $comment->comment = $comment_data;
            $comment->post_id = $post->id;
            $comment->save();
        
            // Conneact to RabbitMQ
            $connection = new AMQPStreamConnection(config('rabbitmq.host'), config('rabbitmq.port'), config('rabbitmq.user'), config('rabbitmq.password'));
            $channel    = $connection->channel();
        
            // Declare exchange
            $channel->exchange_declare('comment', 'topic', false, false, false);
        
            // Create message
            $msg = new AMQPMessage(json_encode([
                'postSlug'   => $postSlug,
                'name'       => $data['name'],
                'comment'    => $comment_data,
                'created_at' => $comment->created_at
            ]));
        
            // Publish message with routing key `post.{postSlug}`
            $channel->basic_publish($msg, 'comment', 'post.' . $postSlug);
        
            $channel->close();
            $connection->close();
        
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            echo "Exception: " . $e->getMessage() . "\n";
            Log::error("Exception: " . $e->getMessage());
        
            return response()->json(['error' => $e->getMessage()], 500);
        }
        
    }
}
