@extends('layouts.app')

@section('content')
<script>
    function removePost(elt){
        try{
            var id= elt.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.id;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            $.ajax({
                    url: '/posts/'+id,
                    type: 'DELETE',
                    success: function(result) {
                        // Do something with the result
                        $("#"+id ).fadeOut('slow', function() { $(this).remove(); });
                        console.log('success');
                        },
                    error: function(e) {
                        $("#"+id ).fadeOut('slow', function() { $(this).remove(); });
                        console.log('error');
                        }
                    });
        }
        catch (err) {
            console.log(err.message);
        }
    }
    function hidePost(elt){
        var id= elt.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.id;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $.post("/hide/"+id, function(){
            $(".hide-post-"+id).fadeOut(1,function() { $(this).remove(); });
            $(".tool-bar-"+id).prepend('<i onclick="unhidePost(this)" class="fas fa-lock padding-2 unhide-post-'+id+'"></i>');
            });
    }
    function unhidePost(elt){
        var id= elt.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.id;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $.post("/unhide/"+id, function(){
            $(".unhide-post-"+id).fadeOut(1,function() { $(this).remove(); });
            $(".tool-bar-"+id).prepend('<i onclick="hidePost(this)" class="fas fa-lock-open padding-2 hide-post-'+id+'"></i>');
            });
    }
    $(document).ready(function(){
        $('.my-posts').click(function(){
            $.ajaxSetup({
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
                });
            var id = $(this).attr("id");
            $.post("/myposts/"+id, function(data){
                //console.log(data);
                $(".all-posts").fadeOut(1, function() { $(this).remove(); });
                    var test ='<div class="col-md-12 all-posts">';
                    if(jQuery.isEmptyObject(data)){
                            test += '<div class="row justify-content-center">You have no posts in this skill environment</div>';
                        }
                    $.each(data, function (index, value){
                        test += '<div class="container" id="'+value._id['$oid']+'">';
                        test += '<div class="row justify-content-center">';
                        test += '<div class="col-md-8">';
                        test += '<div class="card">';
                        test += '<div class="card-header">'+value.title;
                        test += '<div class="tool-bar-'+value._id['$oid']+' float-right">';
                        if (value.hide == 1) {
                            test += '<i onclick="unhidePost(this)" class="fas fa-lock padding-2 unhide-post-'+value._id['$oid']+'"></i>';
                        }
                        else {
                            test += '<i onclick="hidePost(this)" class="fas fa-lock-open padding-2 hide-post-'+value._id['$oid']+'"></i>';
                        }
                        test += '<a href="/posts/'+value._id['$oid']+'/edit" class="fas fa-edit padding-2"></a>';
                        test += '<i onclick="removePost(this)" class="fas fa-trash-alt"></i></div>';
                        test += '</div><div class="card-body"><p>'+value.body+'</p></div>';
                        test += '</div><br></div></div></div>';
                    });
                    test += '</div>'
                    $("#original-card-body").append(test);
                });
            });
        });
</script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-body" id="original-card-body">
                    <h3>{{$skill->title}}
                        <div class="float-right">
                            <button id="{{$skill->id}}" class="my-posts btn btn-light">My Posts</button>
                            <a href="" class="btn btn-light">All Posts</a>
                            <a href="/posts/create/{{$skill->title}}" class="btn btn-dark">Create Post</a>
                        </div>
                    </h3>
                    <hr>
                    <div class="col-md-12 all-posts">
                        @if(count($posts) == 0)
                            <div class="row justify-content-center">
                                <div>Be the first person to post on this skill environment</div>
                            </div>
                        @endif
                        @foreach($posts as $post)
                            <div class="container" id="{{$post['_id']}}">
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header">
                                                {{$post['title']}}
                                                @if(Auth::user()->id == $post['user_id'])
                                                    <div class="tool-bar-{{$post['_id']}} float-right">
                                                        @if($post['hide']==1)
                                                            <i onclick="unhidePost(this)" class="fas fa-lock padding-2 unhide-post-{{$post['_id']}}"></i>
                                                        @endif
                                                        @if($post['hide']==0)
                                                            <i onclick="hidePost(this)" class="fas fa-lock-open padding-2 hide-post-{{$post['_id']}}"></i>
                                                        @endif
                                                        <a href="/posts/{{$post['_id']}}/edit" class="fas fa-edit padding-2"></a>
                                                        <i onclick="removePost(this)" class="fas fa-trash-alt"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body">
                                                <p>{{$post['body']}}</p>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection