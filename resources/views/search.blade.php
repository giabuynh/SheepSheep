@extends('master')

@section('added-css')
  <link rel="stylesheet" href="{{ asset('styles/search.css') }}" type="text/css">
@endsection

@php
  if (!isset($keyword)) $keyword = "";
@endphp

@section('content')
  <section class="home-main-section">
    <div class="search-container">
      <form id="SearchForm">
        {{ csrf_field() }}
        <div class="title">SEARCH RESULT</div>
        <div class="subtitle" id="searchform-message"></div>
        <div class="input-message">
          <div class="inline">
            <input type="text" name="search-key" id="search" onblur="checkRequire(id)"
              value="<?php echo $keyword ?>" placeholder="Enter a keyword">
              <button type="submit" name="search-btn" id="SearchBtn">SEARCH</button>
          </div>
          <span class="messages" id="search-message"></span>
        </div>
      </form>
      <hr>

      <div class="content" id="search-results">

      </div>
      <nav class="pagination">
        <a id="first" href="#" class="pagination_control pagination_control_first">First</a>
        <ol class="pagination_list">
          <li class="pagination-group"> <a id="prev" href="#"></a> </li>
          <li class="pagination-group"> <span id="curr"></span> </li>
          <li class="pagination-group"> <a id="next" href="#"></a> </li>
        </ol>
        <a id="last" href="#" class="pagination_control pagination_control_last">Last</a>
      </nav>
    </div>
  </section>
@endsection

@section('added-script')
  <script type="text/javascript">
    $(".pagination").hide();

    function display_result(response) {
      console.log(response);
      document.getElementById('searchform-message').innerHTML = 'You have searched: <i>'+response["keyword"]+'</i>. We found '+response['total_entries']+' results';
      document.getElementById('searchform-message').style.color = '#A6A6A6';
      $('#search-results').empty();
      var tmp = 0;
      var record_pline = response['record_pline'];
      var html = '';
      var entries = response['entries'];
      entries.forEach((item, i) => {
        if (tmp%record_pline == 0) html += '<div class="line">';
        var reviewerUrl = "/profile/" + item['uname'];
        var blogUrl = "/blog/" + item['blog'];
        html +=
          '<div class="blog-card card">'+
          '<div class="card-img-container">'+
          '<img class="card-img-top img-fluid" src="./images/images/'+item['url']+'" />'+
          '</div>'+
          '<div class="card-body">'+
          '<div class="card-info d-flex flex-row w-100">'+
          item['d']+' by&nbsp;<a href="'+reviewerUrl+'"><i>'+item['uname']+'</i></a>'+
          '</div>'+
          '<a class="card-title" href="'+blogUrl+'">'+item['title']+'</a>'+
          '<p class="card-summary">'+
          item['summary']+
          '</p>'+
          '<a class="black-btn btn" href="'+blogUrl+'">View more</a>'+
          '</div>'+
          '</div>';
        if (tmp%record_pline == record_pline-1) html += '</div>';
        ++tmp;
      });
      if (--tmp%record_pline != record_pline -1) html += '</div>';
      $('#search-results').append(html);

      $('.pagination').show();
      if (response['previous_page'] > 0)
      {
        $("#prev").empty();
        $("#prev").append(response['previous_page']);
        $('#first').show();
        $('#prev').show();
      }
      else
      {
        $('#prev').hide();
        $("#first").hide();
      }
      $('#curr').empty();
      $('#curr').append(response['current_page']);
      if (response['next_page'] > 0)
      {
        $('#next').empty();
        $('#next').append(response['next_page']);
        $('#next').show();
        $('#last').show();
      }
      else
      {
        $('#next').hide();
        $('#last').hide();
      }
    }

    var prev = 0;
    var curr = 1;
    var next = 0;
    var last = 0;

    $('#SearchBtn').on("click", function(event) {
      event.preventDefault();
      $.ajax({
        url: "{{ route('result') }}",
        type: "post",
        data: {
          keyword: $("#search").val(),
          current_page: 1,
          _token: '{{ csrf_token() }}' ,
          record_ppage: 6,
          record_pline: 3
        },
        success: function(response) {
          if (response['keyword'] == "")
          {
            document.getElementById('searchform-message').innerHTML = 'Please enter a keyword.';
            document.getElementById('searchform-message').style.color = 'red';
          }
          else
          {
            display_result(response);
          }
        }
      });
    });

    $('#first').on("click", function(event) {
      event.preventDefault();
      $.ajax({
        url: "{{ route('result') }}",
        type: "post",
        data: {
          keyword: $("#search").val(),
          current_page: 1,
          _token: '{{ csrf_token() }}' ,
          record_ppage: 6,
          record_pline: 3
        },
        success: function(response) {
          if (response['keyword'] == "")
          {
            document.getElementById('searchform-message').innerHTML = 'Please enter a keyword.';
            document.getElementById('searchform-message').style.color = 'red';
          }
          else
          {
            display_result(response);
          }
        }
      });
    });

    $('#prev').on("click", function(event) {
      event.preventDefault();
      $.ajax({
        url: "{{ route('result') }}",
        type: "post",
        data: {
          keyword: $("#search").val(),
          current_page: $("#prev").text(),
          _token: '{{ csrf_token() }}' ,
          record_ppage: 6,
          record_pline: 3
        },
        success: function(response) {
          if (response['keyword'] == "")
          {
            document.getElementById('searchform-message').innerHTML = 'Please enter a keyword.';
            document.getElementById('searchform-message').style.color = 'red';
          }
          else
          {
            display_result(response);
          }
        }
      });
    });

    $('#next').on("click", function(event) {
      event.preventDefault();
      $.ajax({
        url: "{{ route('result') }}",
        type: "post",
        data: {
          keyword: $("#search").val(),
          current_page: $("#next").text(),
          _token: '{{ csrf_token() }}' ,
          record_ppage: 6,
          record_pline: 3
        },
        success: function(response) {
          if (response['keyword'] == "")
          {
            document.getElementById('searchform-message').innerHTML = 'Please enter a keyword.';
            document.getElementById('searchform-message').style.color = 'red';
          }
          else
          {
            console.log('page next');
            display_result(response);
          }
        }
      });
    });

    $('#last').on("click", function(event) {
      event.preventDefault();
      $.ajax({
        url: "{{ route('result') }}",
        type: "post",
        data: {
          keyword: $("#search").val(),
          current_page: $("#next").text(),
          _token: '{{ csrf_token() }}' ,
          record_ppage: 6,
          record_pline: 3
        },
        success: function(response) {
          if (response['keyword'] == "")
          {
            document.getElementById('searchform-message').innerHTML = 'Please enter a keyword.';
            document.getElementById('searchform-message').style.color = 'red';
          }
          else
          {
            display_result(response);
          }
        }
      });
    });
  </script>
@endsection
