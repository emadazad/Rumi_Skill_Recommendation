@extends('layouts.app')

@section('content')
<script>
    function searchSkills(){
            $.ajaxSetup({
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
                });
            var keyword = $("#search").val();
            console.log(keyword);
            $.post("/searchSkills", {keyword: keyword}, function(data){
                console.log(data);
                $("#all-skills").fadeOut(1, function() { $(this).remove(); });
                    var test ='<div id="all-skills" class="row col-md-12">';
                    $.each(data, function (index, value){
                        test += '<a href="/skills/'+value.title+'" class="skill-tag col-md-4">#';
                        test += value.title;
                        test += '<img style="width:24px" src="/storage/17-512.jpg">';
                        test += '<span style="color:#5CA9FF; font-size: 14px ">';
                        test += value.count;
                        test += '</span></a>';
                    });
                    test += '</div>'
                    $(".card-body").append(test);
                });
            };
</script>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <h3>Set of all skills
                                    <span style="font-size: 16px">(Out of {{count($skills)}})</span>
                                </h3>
                            </div>
                            <div class="col-md-5">
                                <div class="row">
                                    <label for="search" class=" col-form-label text-md-right">Search Skill:</label>
                                    <div class="col-md-9">
                                        <input id="search" type="text" name="search" class="form-control" placeholder="e.g. Engineering" onkeyup="searchSkills()">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div id="all-skills" class="row col-md-12">
                            @foreach($skills as $skill)
                                <a href="/skills/{{$skill->title}}" class="skill-tag col-md-4">
                                    #{{$skill->title}}
                                    <img style="width:24px" src="/storage/17-512.jpg">
                                    <span style="color:#5CA9FF; font-size: 14px ">{{$skill->count}}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection