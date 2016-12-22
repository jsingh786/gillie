<style>
    .ngdialog-content {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0 !important;
    }

    #video_popup.title_popup {
        background: #ffffff none repeat scroll 0 0;
        border: medium none;
        height: 600px;
        max-width: 600px !important;
        position: relative;
        text-align: center;
        vertical-align: middle;
        width: auto !important;
    }
    .jwplayer
    {
        position: absolute !important;
        left:28% !important;

    }

</style>
<input id="video_path" value="{{$video_path}}" type="hidden">
<div id="video_popup">
</div>
<script>
    jwplayer("video_popup").setup({
        file: $('input#video_path').val(),
        width: "600px",
        height: "600px",
        stretching: "bestfit",


    });
</script>
