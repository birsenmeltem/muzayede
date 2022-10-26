<?php if(!class_exists('view')){exit;}?><div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="post" onsubmit="return SendSMS(this)">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">SMS Gönder</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i data-dismiss="modal" class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>SMS Gidecek Numara:</label>
                    <input type="text" class="form-control" name="number" value="<?php echo $data["phone"];?>">
                </div>
                <div class="form-group">
                    <label>Mesajınız:</label>
                    <textarea cols="" rows="" class="form-control" name="message"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                <button type="submit" class="btn btn-success">GÖNDER</button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
   function SendSMS(frm) {
       if(window.confirm('Mesaj Gönderilecektir. Onaylıyor musunuz?')) {
           $.post(baseURL + 'orders/sms',$(frm).serialize(),function(data) {
               $('.close').trigger('click');
           })
       }
       return false;
   }
</script>