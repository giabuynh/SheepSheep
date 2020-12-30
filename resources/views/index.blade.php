@extends('master')

@php
  require_once('funcs/func.php');
  $total_pages = ceil($total_entries/$record_ppage);
  $cnt = 0;
@endphp

@section('content')
<section class="home-main-section">
  <div class="content" id="myScroll">
    @foreach ($entries as $entry)
      @if ($cnt%$record_pline == 0) <div class='line'> @endif
      @php
        display_tag($entry)
      @endphp
      @if ($cnt%$record_pline == $record_pline-1) </div> @endif
      @php $cnt++ @endphp
    @endforeach
    @if (--$cnt%$record_pline != $record_pline-1) </div> @endif
  </div>
</section>
@endsection

@section('added-script')
  <script>
    var current_page = 1;
    var total_pages = {{ $total_pages }};
    var record_ppage = {{ $record_ppage }};
    $(document).ready(function() {
      $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 100
          && current_page <= total_pages) {
          ++current_page;
          $.ajax({
            url: "{{ route('autoload') }}",
            type: 'post',
            data: {
              'start_entry':(current_page-1)*record_ppage,
              'record_ppage': record_ppage,
              '_token': '{{ csrf_token() }}'
            },
            success: function(response) {
              // console.log(response);
              var tmp = 0;
              var record_pline = {{ $record_pline }};
              var html = '';
              response.forEach((item, i) => {
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
              $('#myScroll').append(html);
            }
          });
        }
      });
    });
  </script>
@endsection
