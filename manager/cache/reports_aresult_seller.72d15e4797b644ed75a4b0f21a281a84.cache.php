<?php if(!class_exists('view')){exit;}?><script type="text/javascript">
    window.print();
</script>
    <h3><?php echo $data["data"]["start_date"];?> <?php echo $data["data"]["name"];?> Ürün Satanlar Çıktısı</h3>
<?php if( $data["values"] ){ ?>

<table style="width:600px; border-collapse: collapse;" cellpadding="3" cellpadding="3" border="1" align="center">
    <thead>
        <tr>
            <th>Ürün Lot</th>
            <th>Ürün Adı</th>
            <th>Üye Kullanıcı Adı</th>
            <th>Üye Adı Soyadı</th>
            <th>Telefon</th>
        </tr>
    </thead>
    <tbody>
        <?php $counter1=-1; if( isset($data["values"]) && is_array($data["values"]) && sizeof($data["values"]) ) foreach( $data["values"] as $key1 => $value1 ){ $counter1++; ?>

        <tr>
            <td><?php echo $value1["sku"];?></td>
            <td><?php echo $value1["name"];?></td>
            <td><?php echo $value1["user"]["username"];?></td>
            <td><?php echo $value1["user"]["name"];?> <?php echo $value1["user"]["surname"];?></td>
            <td><?php echo $value1["user"]["phone"];?></td>
        </tr>
        <?php } ?>

    </tbody>
</table>
<?php } ?>