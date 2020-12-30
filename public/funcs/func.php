<?php
  function printSVG($src) {
    $svg_file= file_get_contents($src);
    echo $svg_file;
  }

  function display_tag($row) {
    if (mb_strlen($row['summary']) > 50)
    {
      $pos = mb_strpos($row['summary'], ' ', 50);
      $summary = mb_substr($row['summary'], 0, $pos, 'UTF-8') . '...';
    }
    else $summary = $row['summary'];

    $reviewerUrl = '/profile/'.$row['uname'];
    $blogUrl = '/blog/'.$row['blog'];
    $imgUrl = '/images/images/'.$row['url'];

    echo "
    <div class='blog-card card'>
      <div class='card-img-container'>
        <img class='card-img-top' src='$imgUrl'/>
      </div>
      <div class='card-body'>
        <div class='card-info d-flex flex-row w-100'>
          ".$row["d"]." by&nbsp;<a href='$reviewerUrl'><i>".$row["uname"]."</i></a>
        </div>
        <a class='card-title' href='$blogUrl'>".$row['title']."</a>
        <p class='card-summary'>
          ".$summary."
        </p>
        <a class='black-btn btn' href='$blogUrl'>View more</a>
      </div>
    </div>";
  }
 ?>
