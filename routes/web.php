<?php

use Illuminate\Support\Facades\Route;

const DATABASE_NAME = 'blog';
const DB_ARTICLES = DATABASE_NAME . '.articles';
const DB_COMMENTS = DATABASE_NAME . '.comments';

class WebGlobal
{
  /* A MongoDB manager instance to use for DB lookups */
  public $manager;

  function __construct()
  {
    $this->manager = new MongoDB\Driver\Manager(env('MONGO_URL'));
  }

  function article(string $id)
  {
    $query = new MongoDB\Driver\Query
    (
      [ 'id' => $id ],
      [ 'limit' => 1 ]
    );
    $cursor = $this->manager->executeQuery(DB_ARTICLES, $query);

    if ($cursor->isDead())
      return null;
    else
      return $cursor->toArray()[0];
  }

  function comment(string $article_id, string $author, string $text, int $feeling): bool
  {
    try
    {
      $query = new MongoDB\Driver\BulkWrite;

      $query->insert
      ([
        'article_id' => $article_id,
        'author' => $author,
        'text' => $text,
        'feeling' => $feeling
      ]);

      $result = $this->manager->executeBulkWrite(DB_COMMENTS, $query);

      return true;
    } catch (BulkWriteException $e) {
      return false;
    } catch (Exception $e) {
      return false;
    }
  }

  function comments(string $id)
  {
    $query = new MongoDB\Driver\Query
    (
      [ 'article_id' => $id ],
    );
    $cursor = $this->manager->executeQuery(DB_COMMENTS, $query);

    if ($cursor->isDead())
      return null;
    else
      return $cursor->toArray();
  }
};

Route::group([], function ()
{
  $g = new WebGlobal();

  Route::get('/home/', function () use ($g)
  {
    return redirect()->route('article', ['id' => 'activity-meter-rom']);
  });

  Route::get('/article/{id}', function (string $id) use ($g)
  {
    return view('article',
    [
      'article' => $g->article($id),
      'comments' => $g->comments($id)
    ]);
  })->name('article');

  Route::post('/post_comment/{id}', function (string $id) use ($g)
  {
    // Honeypot field for bot protection
    if (!empty(request('etc')))
      return redirect()->back();

    $author = request('author');
    $text = request('comment');
    $feeling = request('feeling');

    $g->comment($id, $author, $text, $feeling);

    return redirect()->back();
  })->name('post_comment');
});
