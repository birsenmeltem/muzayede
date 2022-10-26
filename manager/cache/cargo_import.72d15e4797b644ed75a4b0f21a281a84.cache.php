<?php if(!class_exists('view')){exit;}?><div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Kargo Bilgilerini İçeri Aktar</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i data-dismiss="modal" class="fa fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div id="cargoimporttext">
                <div class="alert alert-warning alert-outline-brand" role="alert">
                    <div class="alert-icon"><i class="fas fa-bullhorn kt-font-secondary"></i></div>
                    <div class="alert-text">
                        Lütfen bekleyiniz ! İşlem tamamlandığında bilgi verilecektir.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var Import = setInterval(function() {
        $.getJSON(baseURL + 'orders/importcode',function(data){
            if(data.finish == 1) {
                clearInterval(Import);
                top.location.reload();
            } else {
                $('#cargoimporttext .alert-text').html(data.message);
            }
        });
    },500);
</script>