<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;

use App\Models\Media;

use App\Models\Collab;
use App\Models\Search;
use App\Models\Account;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use Illuminate\Support\Carbon;
use function PHPSTORM_META\type;
use App\Models\ArtistApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use Dotenv\Exception\ValidationException;

class userController extends Controller
{
   // function to remove a collab, meaning remove an artist from the list of artists that posted.
   public function removeCollab(Request $request)
   {
      $post = Post::Find($request->postId); // get the post by its id
      $post->accounts()->detach(auth()->user()->id); //detach the post from the currently logged in user
      return redirect('/');
   }

   //function to fetch search results based on a search term
   public function searchResults(Request $request)
   {
      $searchTerm = $request->searchTerm; // get the search term from the form
      $searchTerm  = strip_tags($searchTerm); // strip the tags in case the user tried to input harmful html or js
      $visitors = Account::where('username', 'like', "%{$searchTerm}%")->where('artist_status', 0)->get(); //get the visitor accounts who's usernames are similar to the search term
      $artists = Account::where('username', 'like', "%{$searchTerm}%")->where('artist_status', 1)->get(); //same for artists


      $posts = Post::where('caption', 'like', "%{$searchTerm}%")->with('media')->get(); //get posts who contain the search term in their caption



      return view('search-results', compact('searchTerm', 'visitors', 'artists', 'posts'));
   }

   //function for the admin to delete a user's account
   public function deleteAccount(Request $request)
   {

      $user = Account::Find($request['profile-id']); //find the account
      if ($user) {
         $posts = $user->posts()->get(); //get the account's posts
         foreach ($posts as $post) {
            // get the posts where the user is the only poster
            if ($post->accounts()->count() == 1) {

               $medias = $post->media; //get the media (videos audio or images) for the post


               foreach ($medias as $media) {
                  $filePath = $media->addr;

                  if (File::exists(public_path($filePath))) {

                     File::delete(public_path($filePath)); //delete them from storage
                  }
               }

               $post->delete(); //delete the post
            }
         }


         $filePath = $user->avatar; //get the user's profile photo file path
         if ($filePath != '/storage/avatars/default.png') {
            if (File::exists(public_path($filePath))) {

               File::delete(public_path($filePath)); // delete it if its not the default picture used for new accounts.
            }
         }
         $user->delete(); //finally, delete the user.
      }

      return redirect('/');
   }

   //function to delete a comment, only an admin or the comment's owner can delete a comment.
   public function deleteComment(Request $request)
   {
      $comment = Comment::Find($request['comment-id'])->delete(); //get the comment by id and delete it
      return back();
   }

   //function for a user to delete their own account
   public function deleteOwnAccount()
   {
      $user = Account::Find(auth()->user()->id); //find the logged in user's account
      if ($user) {
         $posts = $user->posts()->get(); //get the account's posts
         foreach ($posts as $post) {
            // get the posts where the user is the only poster
            if ($post->accounts()->count() == 1) {

               $medias = $post->media; //get the media (videos audio or images) for the post


               foreach ($medias as $media) {
                  $filePath = $media->addr;

                  if (File::exists(public_path($filePath))) {

                     File::delete(public_path($filePath)); //delete them from storage
                  }
               }

               $post->delete(); //delete the post
            }
         }


         $filePath = $user->avatar; //get the user's profile photo file path
         if ($filePath != '/storage/avatars/default.png') {
            if (File::exists(public_path($filePath))) {

               File::delete(public_path($filePath)); // delete it if its not the default picture used for new accounts.
            }
         }
         $user->delete(); //finally, delete the user.
      }
      return redirect('/');
   }

   //function to modify the user's bio and profile picture
   public function modifyProfile(Request $request)
   {
      //get the logged in user's account
      $user = Account::Find(auth()->user()->id);

      //change their bio if they input a new one, only after stripping potential harmful scripts.
      if ($request['bio']) {
         $request['bio'] = strip_tags($request['bio']);
         $user->update(['bio' => $request['bio']]);
      }
      //get the new profile picture
      $file  = $request->file('pfp');
      if ($file) {
         // if they input a new profile picture
         try {
            $this->validateImageUpload($file); //validate the file to make sure it is a supported image
         } catch (ValidationException $e) {

            return back()->with('failure', $e->getMessage())->withInput($request->input()); // if it isnt go back to the modify page with a warning
         }
         $fileName = $user->id . '-' . uniqid() . '.' . $file->getClientOriginalExtension(); //generate a unique name for the file



         $storagePath = '/avatars' . '/'; // make the path to store it in the server


         $file->storeAs('public' . $storagePath, $fileName); // store it in the server

         $storagePath = '/storage' . $storagePath . $fileName; // concatenate that information to put it in the path attribute of the image

         $filePath = $user->avatar; // get the user's current profile photo, 
         if ($filePath != '/storage/avatars/default.png') {
            if (File::exists(public_path($filePath))) {

               File::delete(public_path($filePath)); // delete it if its not the default image
            }
         }
         $user->update(['avatar' => $storagePath]); // set the new profile photo
      }



      return redirect()->route('profile', ['username' => $user->username]);
   }

   //function for an admin to accept or reject an artist application
   public function updateArtistApplication(Request $request)
   {

      $action = $request->input('status'); // get the action from the page, 1 means approve, 0 means reject
      $artistApplication = ArtistApplication::Find($request->input('id')); //get the application form from the db

      if ($action == "1") { //if the action is approve

         $artistApplication->update(['status' => 'approved']); // update the form status

         $artist = $artistApplication->account(); // get the artist's account
         $artist->update(['type' => 'artist']); //make his account of type artist
         $artist->update(['artist_status' => '1']); //make his account a verified artist

         return redirect('/admin');
      } else if ($action == "0") {

         $artistApplication->update(['status' => 'rejected']); // update the form status
         $artist = $artistApplication->account();  // get the artist's account
         $artist->update(['type' => 'visitor']); // this is already the default value but just in case there was  an error, make the account of type visitor
         $artist->update(['artist_status' => '0']); // this is already the default value but just in case there was  an error, make the account a non verified artist
         return redirect('/admin');
      }
   }

   //function to load homepage with content
   public function loadHomepage(Request $request)
   {
      //get the page number 
      $page = $request->input('page');
      //get the category (if the user clicks any category buttons on the front end to filter posts)
      $filter = $request->input('category') ? $request->input('category') : 'all';
      //get the logged in user
      $user = Account::find(Auth::user()->id);

      //if no user redirect to log in
      if (!$user) {
         return redirect()->route('login');
      }

      //get the users the logged in user follows to load their posts on his feed
      $followedUsers = $user->followed()->with('posts')->get();

      //separate the post instances from the follow instances because its useless framing.
      $posts = [];
      foreach ($followedUsers as $followedUser) {
         foreach ($followedUser->collabs as $collaboration) {
            array_push($posts, $collaboration->post);
         }
      }
      //filter the posts if the user chose a category
      $filteredPosts = [];
      foreach ($posts as $post) {
         if ($filter === 'all' || $filter == $post->art_type || $filter == $post->type) {
            $filteredPosts[] = $post;
         }
      }
      //get the posts for the page (20 posts per page) using the page number $page
      $posts = $filteredPosts;

      //filter them more to exclude duplicate entries from collab posts where the user follows multiple of the collabers.
      $uniqueposts = [];
      $seenpostIds = [];
      for ($i = 0; $i < count($posts); $i++) {
         $post = $posts[$i];
         $postId = (int) $post->id;

         if (!in_array($postId, $seenpostIds)) {
            $uniqueposts[] = $post;
            $seenpostIds[] = $postId;
         }
      }

      $posts = $uniqueposts;
      //for the last page button the page number is -1 so this finds the last page 
      if ($page < 0)
         $page = floor(count($posts) / 20);


      $startIndex = $page * 20;

      //get the 20 post range needed.
      $posts = array_slice($posts, $startIndex, 20);

      return view('home-page', compact('posts', 'page'));
   }

   //function to delete a post, accessible to the admin and the post owner (or all of the collaborators)
   public function deletePost(Request $request)
   {
      //get post id and get the post using the id
      $postId = $request->input('postId');
      $post = Post::Find($postId);

      //if the post esists get the media (files, images audio and video)
      if ($post) {
         $medias = $post->media;

         //delete each file from storage
         foreach ($medias as $media) {
            $filePath = $media->addr;

            if (File::exists(public_path($filePath))) {

               File::delete(public_path($filePath));
            }
         }
      }
      //delete post instance
      $post->delete();
      return redirect('/');
   }

   //function to delete an entire conversation between 2 users (only accessible to the users on each end)
   public function deleteConversation(Request $request)
   {
      //get ids of the users in the conversation
      $sender = $request->input('userId');
      $receiver = auth()->user()->id;

      //delete both sides of the conversation (this also deletes the messages because they have a foreign key attribute of the converation id set to cascade on delete)
      $conversation = Conversation::where('sender_id', $sender)
         ->where('receiver_id', $receiver)
         ->delete();

      $conversation = Conversation::where('sender_id', $receiver)
         ->where('receiver_id', $sender)
         ->delete();

      return redirect()->route('messages-page', [$receiver => auth()->user()->id]);
   }

   //function to remove the like of a user from a post
   public function removeLike(Request $request)
   {
      //get post through id
      $post = Post::find($request->input('postId'));
      //get logged in user
      $user = Account::Find(auth()->user()->id);

      //if both instances exist then detach the like from the user and the post (in the likes table in the db)
      if ($post && $user) {
         $post->likes()->detach($user);
      }
      return back();
   }

   //function to add like from a user to a post
   public function addLike(Request $request)
   {
      //find the post through its id
      $post = Post::find($request->input('postId'));
      //get the logged in user
      $user = Account::Find(auth()->user()->id);

      //if both instances exist then attach the like from the user to the post (in the likes table in the db)
      if ($post && $user) {
         $post->likes()->attach($user);
      }

      return back();
   }


   //function to store a comment in the databse (or post it to a post)
   public function storeComment(Request $request)
   {
      // validate the incoming data
      $request->validate([
         'commentcontent' => 'required|string|max:255',
      ]);

      // find the post based on the provided id
      $post = Post::findOrFail($request->input('postId'));

      // get the logged in user
      $user = Auth::user();

      // create a new Comment instance
      $comment = new Comment;
      $comment->content = $request->commentcontent;

      // associate the comment with the post
      $post->comments()->create([
         'content' => $comment->content,
         'account_id' => $user->id,
      ]);


      return back();
   }

   //function to validate image files
   private function validateImageUpload($uploadedFiles)
   {
      //accepted extentions
      $allowedExtensions = ['jpg', 'jpeg', 'png']; // Allowed image extensions

      //check if the user selected any files
      if (!$uploadedFiles) {
         throw new ValidationException('Please select images to upload.');
      }
      //for each file check if the format is accepted
      foreach ($uploadedFiles as $uploadedFile) {
         $mediaExtension = $uploadedFile->getClientOriginalExtension(); //get the exntention
         //check with the accepted extention array
         if (!in_array($mediaExtension, $allowedExtensions)) {
            throw new ValidationException('Invalid media file type.');
         }
      }
   }


   //function to validate video and audio files
   private function validateMediaAndThumbnail($mediaFile, $thumbnailFile)
   {

      //accepted extentions
      $allowedMediaExtensions = ['mp4', 'mov', 'avi', 'mp3', 'ogg'];


      //check if the user entered both the media and its thumbnail (cover photo)
      if (!$mediaFile || !$thumbnailFile) {
         throw new ValidationException('Please select both media and thumbnail files.');
      }

      //get the media and thumbnail extentions from the files
      $mediaExtension = $mediaFile->getClientOriginalExtension();
      $thumbnailExtension = $thumbnailFile->getClientOriginalExtension();

      //check if the exntentions are acceptable
      if (!in_array($mediaExtension, $allowedMediaExtensions)) {
         throw new ValidationException('Invalid media file type.');
      }

      if (!in_array($thumbnailExtension, ['jpg', 'jpeg', 'png'])) {
         throw new ValidationException('Invalid thumbnail file type.');
      }
   }

   //function to get collaborators and return them in json format, for the create post page.
   public function searchCollaborators(Request $request)
   {
      $searchTerm = $request->input('username'); // get the username from the search

      $searchResults = Account::where('username',  $searchTerm)->get(); // get the account with an exact match in the username

      //check if an account was found (count would be 1 if yes because usernames are unique, 0 if not)
      $valid = $searchResults->count() > 0;


      //return the results in json for the ajax function in the create post js script.
      return response()->json([
         'valid' => $valid,
         'collaborators' => $searchResults,
      ]);
   }

   //function for the search bar in th emessages page
   public function searchMessages(Request $request)
   {
      //get the logged in user id
      $loggedInUser = auth()->user();
      $userId = $loggedInUser->id;
      //get the search term from the form
      $searchTerm = $request->search;
      //get the ids of users who's usernames are similar to the search term
      $corresponderIds = Account::where('username', 'like', "%{$searchTerm}%")
         ->pluck('id'); // get only ids for efficiency

      //get the unique conversations as explained in the loadMessagesPage function ------
      $conversations = Conversation::where(function ($query) use ($userId, $corresponderIds) {
         $query->where(function ($subquery) use ($userId, $corresponderIds) {
            $subquery->where('sender_id', $userId)
               ->whereIn('receiver_id', $corresponderIds);
         })
            ->orWhere(function ($subquery) use ($userId, $corresponderIds) {
               $subquery->whereIn('sender_id', $corresponderIds)
                  ->where('receiver_id', $userId);
            });
      })
         ->orderBy('created_at', 'asc')
         ->get();
      $uniqueConversations = [];
      $seenConversationIds = [];
      for ($i = 0; $i < count($conversations); $i++) {
         $conversation = $conversations[$i];
         $conversationId1 = (int) $conversation->sender_id . '-' . (int) $conversation->receiver_id;
         $conversationId2 = (int) $conversation->receiver_id . '-' . (int) $conversation->sender_id;
         if (!in_array($conversationId1, $seenConversationIds) && !in_array($conversationId2, $seenConversationIds)) {
            $uniqueConversations[] = $conversation;
            $seenConversationIds[] = $conversationId1;
            $seenConversationIds[] = $conversationId2;
         }
      }
      $conversations = $uniqueConversations;
      //    --------------

      if (!$conversations) {
         //if no conversations exist related to the search term then go to the normal messages page
         return redirect()->route('messages-page', [$userId => auth()->user()->id]);
      }
      //if they exist show them in the messages page
      return view('messages', compact('conversations'));
   }





   //function to load messages between 2 users, (like the first message page but this one loads a conversation between 2 users as well)
   public function loadMessages($userId, $corresponderId)
   {
      //all of this is the same as the loadMessagesPage function ------
      if ($userId != auth()->user()->id) {
         //check if the logged in user has the right to access this conversation or if they just entered the url
         return redirect()->route('messages-page', [$userId => auth()->user()->id]);
      }
      $corresponder = Account::find($corresponderId);

      $conversations = Conversation::where(function ($query) use ($userId) {
         $query->where('receiver_id', $userId)
            ->orWhere('sender_id', $userId);
      })
         ->groupBy('id')
         ->orderBy('created_at', 'desc')
         ->get();


      $uniqueConversations = [];
      $seenConversationIds = [];

      for ($i = 0; $i < count($conversations); $i++) {
         $conversation = $conversations[$i];
         $conversationId1 = (int) $conversation->sender_id . '-' . (int) $conversation->receiver_id;
         $conversationId2 = (int) $conversation->receiver_id . '-' . (int) $conversation->sender_id;
         if (!in_array($conversationId1, $seenConversationIds) && !in_array($conversationId2, $seenConversationIds)) {
            $uniqueConversations[] = $conversation;
            $seenConversationIds[] = $conversationId1;
            $seenConversationIds[] = $conversationId2;
         }
      }
      $conversations = $uniqueConversations;
      if (!$conversations) {
         return redirect()->route('messages-page', [$userId => auth()->user()->id]);
      }
      //-------


      //load the messages between the 2 users (using the ids from the url)
      $messages = Message::where(function ($query) use ($userId, $corresponderId) {
         $query->where(function ($subquery) use ($userId, $corresponderId) {
            $subquery->where('sender_id', $userId)
               ->where('receiver_id', $corresponderId); // messages from the user to the other one
         })
            ->orWhere(function ($subquery) use ($userId, $corresponderId) {
               $subquery->where('sender_id', $corresponderId)
                  ->where('receiver_id', $userId); // messages from the other one to the user
            });
      })
         ->orderBy('created_at', 'asc')  // order messages by creation date (desc)
         ->get();

      return view('messages', compact('conversations', 'messages', 'corresponder'));
   }

   //function for loading a message page with a specific user faccounting for if the conversation hadnt existed previously
   public function loadOrStartConversation($receiverId)
   {
      $senderId = auth()->user()->id; //get the sender id (logged in user)

      //check if the conversation instance exists, in both directions
      $conversation = Conversation::where('sender_id', $senderId)
         ->where('receiver_id', $receiverId)
         ->first();
      if (!$conversation) {
         $conversation = Conversation::where('sender_id', $receiverId)
            ->where('receiver_id', $senderId)
            ->first();
      }

      //if it doesnt exist in either direction
      if (!$conversation) {

         //create the conversation instance
         $conversation = Conversation::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId
         ]);

         //send the first message 
         $message = 'This conversation was started by ' . Account::Find($senderId)->username . '.';
         $message = Message::create([
            'content' => $message,
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'conversation_id' => $conversation->id
         ]);
      } else {
         //if one of the directions of conversation exist then redirect to the regular messages page between 2 users
         return redirect()->route('messages', ['userId' => $senderId, 'corresponderId' => $receiverId]);
      }
      // redirect to the messages page between 2 users after creating the new conversation
      return redirect()->route('messages', ['userId' => $senderId, 'corresponderId' => $receiverId]);
   }

   //function to store a message in the database (or in other words to send a message to a user)
   public function storeMessage(Request $request)
   {
      //validate message and strip tags for safety
      $request->validate([

         'messagetosend' => 'max:250'
      ]);
      $message = strip_tags($request['messagetosend']);
      //if the message is empty then return to the messages page
      if ($message == null)
         return back();
      //get the sender id (logged in user)
      $userId = auth()->user()->id;
      //the receiver id (from the message form)
      $corresponderId = Account::find($request['corresponder'])->id;


      //get the conversation instance (to check if it exists)
      $conversation = Conversation::where('sender_id', $userId)
         ->where('receiver_id', $corresponderId)
         ->first();

      if (!$conversation) {
         // create a new conversation
         $conversation = Conversation::create([
            'sender_id' => $userId,
            'receiver_id' => $corresponderId
         ]);
      }

      // create a new message within the conversation
      $message = Message::create([
         'content' => $message,
         'sender_id' => $userId,
         'receiver_id' => $corresponderId,
         'conversation_id' => $conversation->id
      ]);



      return back();
   }

   //function to load the main messages page.
   public function loadMessagesPage()
   {
      //get the logged in user
      $userId = auth()->user()->id;

      //get teh conversations they were in from the conversation table.
      $conversations = Conversation::where(function ($query) use ($userId) {
         $query->where('receiver_id', $userId)  // Sender can be the logged-in user
            ->orWhere('sender_id', $userId);
      })
         ->groupBy('id')  // Group by participant and conversation ID
         ->orderBy('created_at', 'desc')  // Order by latest message within each group
         ->get();

      //the conversation table saved both directions of a conversation, so to avoid mirror instances (u4 messaged u1 and u1 messaged u4 for example), we get the unique conversations only by iterating through the conversations, filling the seen array for every new conversation,array and keeping the only the unique ones that dont exist in the seen array
      $uniqueConversations = [];
      $seenConversationIds = [];

      for ($i = 0; $i < count($conversations); $i++) {
         $conversation = $conversations[$i];
         $conversationId1 = (int) $conversation->sender_id . '-' . (int) $conversation->receiver_id; // create a unique ID for conversation direction
         $conversationId2 = (int) $conversation->receiver_id . '-' . (int) $conversation->sender_id; // create a unique ID for the other conversation direction
         //if the unique ids 1 and 2 dont exist in the seen array then its a unique conversation
         if (!in_array($conversationId1, $seenConversationIds) && !in_array($conversationId2, $seenConversationIds)) {
            $uniqueConversations[] = $conversation;
            $seenConversationIds[] = $conversationId1;
            $seenConversationIds[] = $conversationId2;
         }
      }
      //pass the unique conversations
      $conversations = $uniqueConversations;
      return view('messages', compact('conversations'));
   }

   //function to remove a follower, meaning forcing a follower to unfollow you.
   public function removeFollower($userId)
   {
      //the user to be unfollowed
      $user = Account::find(auth()->user()->id);
      //the unwanted follower
      $follower = $userId;

      //detach the follow from the follow table using account model's followers function
      $user->followers()->detach($follower);

      return back();
   }


   //function to unfollow a user 
   public function unFollow($userId)
   {
      //the user intiating the unfollow
      $follower = Account::find(auth()->user()->id);
      //the user to be unfollowed
      $followedId = Account::find($userId);

      //detach them from the follow table  the same way the follow was attached in the function below
      $follower->followed()->detach($followedId);

      return back();
   }


   //function to follow someone
   public function addFollow($userId)
   {
      //get the logged in user, the one who will follow someone else
      $follower = Account::find(auth()->user()->id);

      //get the user to be followed through their userId entered in the url
      $followedId =
         Account::find($userId);

      //attach the follow from the follow table (using the account model's followed function)
      $follower->followed()->attach($followedId);

      return back();
   }

   //function to load information about a single post for the post card page
   public function showsinglepost($username, $post_id)
   {
      // find the account by username, entered in the url
      $account = Account::where('username', $username)
         ->first();

      // check if account exists
      if (!$account) {
         return abort(404);
      }

      //get the post via the postId entered in the url and the account instance, with the media (the audio video or image files)
      $post = $account->posts()
         ->where('post_id', $post_id)
         ->with('media')
         ->first();



      // check if post exists
      if (!$post) {
         return abort(404);
      }


      // return the view with compact data
      return view('post-card', compact('post', 'account'));
   }


   //function to load the activity profile (like a visitor profile) for artists so they can see their activity on the site, this is private to them only unlike for visitors
   public function loadActivityProfile()
   {
      $profile = Account::find(auth()->user()->id); // get the account instance

      //posts they liked
      $likes = $profile->likes()->with('post.media')->get();
      $likedPosts = [];
      //separate the posts and media from the like instances
      foreach ($likes as $like) {
         $likedPosts[] = $like->post;
      }

      //their comments
      $comments =  $profile->comments()->with('post.media')->get();

      //who they follow
      $followeds = $profile->followed()->get();
      //who follows them
      $followers = $profile->followers()->get();
      //if theyre viewing their own profile
      $isViewingOwnProfile = auth()->user()->id === $profile->id;
      //if they are artists take them to their activity page
      if ($profile->artist_status) {
         return view('activity-profile', compact('profile', 'likedPosts', 'comments', 'followeds', 'followers', 'isViewingOwnProfile'));
      } else {
         //if not redirect them to their own profile (non artists cannot access this via anything on the site to its a safety measure incase they type in the url)
         return redirect()->route('profile', [auth()->user()->username]);
      }
   }

   //function to load a profile page
   public function loadProfile($username)
   {
      //get the account of the profile based on the username in the url
      $profile = Account::where('username', $username)->first();

      if (!$profile) {
         return abort(404);
      }
      //if the profile owner is an artist
      if ($profile->artist_status) {
         //get their posts with the media (with the images audio and video relations)
         $posts = $profile->posts()->with([
            'media' => function ($query) {
               $query->where('type', 'image');
            },
         ])->get();

         //get if the logged in user follows the profile owner
         $profileisFollowedByUser = $profile->followers()->where('follower_id', auth()->user()->id)->exists();
         //get if the profile owner follows the user
         $profilefollowsUser = Account::find(auth()->user()->id)->followers()->where('follower_id', $profile->id)->exists();
         //get if the logged in user in viewing their own profile
         $isViewingOwnProfile = auth()->user()->id === $profile->id;

         return view('artist_profile', compact('posts', 'profile', 'profileisFollowedByUser', 'isViewingOwnProfile', 'profilefollowsUser'));
      } else {
         //if the profile owner is not an artist

         //get the posts they like
         $likes = $profile->likes()->with('post.media')->get();

         //separate only the post and media relation without the like instance
         $likedPosts = [];
         foreach ($likes as $like) {
            $likedPosts[] = $like->post;
         }
         //get the comments they posted
         $comments =  $profile->comments()->with('post.media')->get();
         //get who they follow, not who follows them because they are not followable if they are not artists
         $followedArtists = $profile->followed()->get();
         //get if the logged in user in viewing their own profile
         $isViewingOwnProfile = auth()->user()->id === $profile->id;
         return view('activity-profile', compact('profile', 'likedPosts', 'comments', 'followedArtists', 'isViewingOwnProfile'));
      }
   }



   //function to store a post in the database
   public function storepost(Request $request)
   {
      $user = Account::find(auth()->user()->id); // get the logged in user



      try {
         $postType = $request->postType; //get post type audio or image or video
         $artType = $request->artType; //get art type
         $thumbnailFile = $request->file('thumbnail'); //if the user chose audio or video they are required to input a thumbnail, a cover photo.


         // Validation based on post type
         switch ($postType) {
            case 'image':
               $uploadedMedia
                  = $request->file('images');
               $this->validateImageUpload($uploadedMedia); // if post type image, validate the images
               break;
            case 'video':
            case 'audio':
               $uploadedMedia = $request->file('media');
               $this->validateMediaAndThumbnail($uploadedMedia, $thumbnailFile); //else validate audio or video
               break;
            default:
               throw new ValidationException('Invalid post type');
         }
         // if file formats invalid or no file was input then it throws an error
      } catch (ValidationException $e) {
         return back()->with('failure', $e->getMessage())->withInput($request->input()); // returns the user to the create post page with a warning
      }
      // get the collaborators, selected in the page via js, passed in as a long string separated by ',', explode() seprates them into an actual array.
      $collabUsernames = explode(",", $request->input("collaborators"));

      //validate the caption and strip tags to prevent harmful scripts
      $request->validate([
         'caption' => 'required|max:200'
      ]);
      $request['caption'] = strip_tags($request['caption']);

      // create the post instance
      $post = Post::create([
         'art_type' => $artType,
         'type' => $postType,
         'caption' => $request['caption']
      ]);

      // put all the entered media into one single array called files, depending on whether or not uploadedmedia is an array the input of array_merge changes.
      if (is_array($uploadedMedia)) {
         $files = array_merge($uploadedMedia, [$thumbnailFile]);
      } else {
         $files = array_merge([$uploadedMedia], [$thumbnailFile]);
      }

      $mediaData = []; // Array to store media data

      foreach ($files as $file) {
         if ($file) {
            // make unique name for the file
            $fileName = $user->id . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

            // get the file extention
            $fileType = $file->getClientOriginalExtension();

            // determine storage path based on file type determined by the exntention
            $storagePath = '/uploads/images/';
            if (in_array($fileType, ['mp4', 'mov', 'mpeg'])) {
               $fileType = 'video';
               $storagePath = '/uploads/videos/';
            } elseif (in_array($fileType, ['mp3', 'wav'])) {
               $fileType = 'audio';
               $storagePath = '/uploads/audio/';
            } else {
               $fileType = 'image';
            }

            //store the file
            $file->storeAs('public' . $storagePath, $fileName);

            // add the new file's information to the media data array so it can be added to the media table later
            $mediaData[] = [
               'post_id' => $post->id,
               'addr' => '/storage' . $storagePath . $fileName,
               'type' => $fileType
            ];
         }
      }

      // save media data in database
      if (!empty($mediaData)) {
         Media::insert($mediaData);
      }

      //add the collaboration links in the collab table (to link the post to all the collaborators)
      if (!empty($collabUsernames)) {
         foreach ($collabUsernames as $collaborator) {
            if ($collaborator) {
               //get the collaborator account
               $collaborator = (Account::Where('username', $collaborator)->get()->first())->id;
               if (!empty($collaborator)) {
                  //check whether or not the entry in collab already exists
                  $existingEntry = Collab::where('account_id', $collaborator)
                     ->where('post_id', $post->id)
                     ->first();
                  //if it doesnt then enter it
                  if (!$existingEntry) {
                     Collab::create([
                        'account_id' => $collaborator,
                        'post_id' => $post->id
                     ]);
                  }
               }
            }
         }
      }
      //check whether or not the poster was linked to the post (because they can select themselves as a collaborator in the create post page)
      $existingEntry = Collab::where('account_id', $user->id)
         ->where('post_id', $post->id)
         ->first();
      //if not then add it to the collab table
      if (!$existingEntry) {
         Collab::create([
            'account_id' => $user->id,
            'post_id' => $post->id
         ]);
      }

      return redirect('/')->with('success', 'Post created');
   }


   //function to log out
   public function logout(Request $request)
   {
      auth()->logout(); // logs out the logged in user
      return redirect('/')->with('success', 'Logged out !');
   }
   //function to direct the user to the correct page
   public function showcorrecthomepage(Request $request)
   {
      if (auth()->check()) {
         return redirect()->route('home'); // if logged in go to homepage
      } else {
         return view('welcome-page'); // if not go to log in page
      }
   }

   //function to log in
   public function login(Request $request)
   { // validate the log in data
      $request->validate([
         'username_or_email' => 'required|string',
         'password' => 'required|string',
      ]);
      // get the remember me checkbox status
      $remember = $request->has('remember_me');
      // allow the user to input their username or email
      $loginField = filter_var($request->input('username_or_email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
      // make an array containing the login credentials
      $loginCredentials = [
         $loginField => $request->input('username_or_email'),
         'password' => $request->input('password'),
      ];


      // enter the log in credentials and the remember me status to auth::attempt for logging in, it returns true if the credentials match and uses the remember me variable to determine whether or not the user will stay logged in after the browser is closed.
      if (Auth::attempt($loginCredentials, $remember)) {

         $request->session()->regenerate(); // log the user in

         return redirect('/')->with('success', 'Logged in successfully!');; // Redirect to intended route or dashboard
      } else {

         return
            redirect('/')->with('failure', 'Failed to log in');
      }
   }

   //function to create a new application form for an artist account
   public function artistApplication(Request $request)
   {  // validate the required data
      try {
         $validatedData = $request->validate([

            'fullname' => ['required', 'string', 'max:100', 'unique:artist_application_form'],
            'phone' => ['required', 'string', 'max:30', 'unique:artist_application_form'],
            'description' => ['required', 'string', 'max:511'],
            'portfolio' => ['required', 'string', 'url', 'max:255'],
         ]);
         $validatedData['description'] = strip_tags($request['description']);
      } //strip potential harmful scripts
      catch (ValidationException $e) {
         $user = Account::Find($request->userId);
         $user->delete();
         return redirect()->route('signup-login')->with('failure', $e->getMessage())->withInput($request->input());
      }
      //create the form instance
      if (Account::find($request->userId)) {
         $application = ArtistApplication::create([

            'fullname' => $validatedData['fullname'],
            'phone' => $validatedData['phone'],
            'art_description' => $validatedData['description'],
            'portfolio' => $validatedData['portfolio'],
            'account_id' => $request->userId
         ]);
      } else
         return redirect('/')->with('failure', 'Error occured');


      $user = Account::Find($request->userId); // get the new user id from the previous sign up form they got redirected from.
      auth()->login($user); // log in the new user, they will have a visitor account until approved.
      return redirect('/')->with('success', 'Account Created !');
   }
   //function to create a new user
   public function create_user(Request $request)
   {
      $isArtist = $request->has("userType"); // get the checkbox status, checked returns true, unchecked returns false.
      // validate the input data
      $validatedData = $request->validate([

         'username' => ['required', 'string', 'unique:account', 'max:255'],
         'email' => ['required', 'string', 'email', 'unique:account', 'max:255'],
         'password' => ['required', 'string', 'min:2'],
         'type' => $isArtist ? 'artist' : 'visitor',

      ]);

      // set the default type to visitor, even when an account applies for an artist type, they wont be artists until their form is approved
      $type = 'visitor';

      // Create a new user instance
      $user = Account::create([

         'username' => $validatedData['username'],
         'email' => $validatedData['email'],
         'password' => $validatedData['password'], // password is hashed by default before storing, the hashing is included in the account model
         'type' => $type,
      ]);
      if ($isArtist) {
         // if they checked 'apply for an artist account' redirect them to fill an artist application form
         return view('artist-application-form', compact('user'));
      }
      //log the user in and redirect to the home page
      auth()->login($user);
      return redirect('/')->with('success', 'Account Created !');
   }
}
