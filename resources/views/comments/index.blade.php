<div id="page-header">
    <h4>댓글</h4>
</div>

<div id="form__new__comment">
    @if($currentUser)
        @include('comments.partial.create')
    @else
        @include('comments.partial.login')
    @endif
</div>

<div id="list__comment">
    @forelse($comments as $comment)
        @include('comments.partial.comment', [
        'parentId' => $comment->id,
        'isReply' => false,
        ])
    @empty
    @endforelse
</div>

@section('script')
@parent
<script>
    // 댓글 삭제 요청을 처리한다.
    $('.btn__delete__comment').on('click', function (e) {
        var commentId = $(this).closest('.item__comment').data('id'),
            articleId = $('article').data('id');

        if (confirm('댓글을 삭제합니다.')) {
            $.ajax({
                type: 'POST',
                url: "/comments/" + commentId,
                data: {
                    _method: "DELETE"
                }
            }).then(function () {
                $('#comment_' + commentId).fadeOut(1000, function () {
                    $(this).remove();
                });
            });
        }
    });
</script>
@endsection