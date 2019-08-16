<div class="modal fade" id="info">
    <div class="modal-dialog modal-md">
        <div class="modal-content">  
            <div class="info_content"></div>                      
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
        $('a[href="#info"]').on('click',function(){
            $('#info').modal('show');   
            $('.info_content').load("{{ url('/loading') }}");   
            var id = $(this).data('id');
            var url = "{{ url('/load/info/') }}/"+id;
            console.log(url);
            setTimeout(function(){
                $('.info_content').load(url);
            },1000);
        });
</script>