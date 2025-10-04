<!DOCTYPE html>
<html>
<head>
<title>Bloggy Long Face</title>
</head>

<body text="#ffffff" bgcolor="#DBDBFF" link="#22FF22" alink="#226622" vlink="#22FF22">
<table cellspacing="8" cellpadding="8" border="4" background={{ asset('images/bg2.gif') }} width="1024">
  <tr>
    <td bgcolor="#111">
      <img src="{{ asset('images/dlf.gif') }}">
      <img src="{{ asset('images/new.gif') }}"><a href="{{ route('article', ['id' => 'activity-meter-rom']) }}"><blink>Nintendo DS Activity Meter ROM</blink></a>
      <marquee behavior="scroll" direction="left" scrollamount="5">Welcome to Doggy Long Face dot com!</marquee>
    </td>
  </tr>

  <tr>
    <td background={{ asset('images/bg.gif') }}>
      @if (empty($article))
      hello
      @else
      <h2><center>{{ $article->title }}</center></h2>
      <font face="Georgia">{!! nl2br($article->text) !!}</font>
      @endif
    </td>
  </tr>

  <tr>
    <td bgcolor="#111">
      <form action="{{ route('post_comment', ['id' => $article->id]) }}" method="post">
      <center><h3>Shoutbox</h3></center>
      @csrf
        <label for="author">Your name:</label><br>
        <input type="text" id="author" name="author" required><br><br>

        <textarea id="comment" name="comment" rows="4" cols="50" required></textarea><br><br>

        <input type="text" name="etc" style="display:none" autocomplete="off">

        <label for="feeling">Feeling:</label><br>
        <select id="feeling" name="feeling" required>
          <option value="0">None</option>
          <option value="1">LOL</option>
          <option value="2">Happy dance!</option>
          <option value="3">Crying D':</option>
          <option value="4">Sigh...</option>
          <option value="5">Confused</option>
          <option value="6">So angry I'm banging my head on the wall!!</option>
        </select>

        <input type="submit" value="Submit">
      </form>
    </td>
  </tr>

  @if (!empty($comments))
  @foreach ($comments as $comment)
  <tr>
    <td bgcolor="#111">
    @if (!empty($comment->feeling))
      <img src={{ asset('images/feeling/' . $comment->feeling . '.gif') }}>
    @endif
    {{ $comment->author }} says...
    <p>{{ $comment->text }}</p>
    </td>
  </tr>
  @endforeach
  @endif

</table>
</body>
</html>
