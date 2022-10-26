<?php if(!class_exists('view')){exit;}?><script type="text/javascript">
    window.print();
</script>
    <h3>Pey verenler sonuç raporu <?php if( $f["date"] ){ ?> (<?php echo $f["date"];?>) <?php } ?></h3>
<?php if( $data["data"] ){ ?>

<table style="width:600px; border-collapse: collapse;" cellpadding="3" cellpadding="3" border="1" align="center">
    <thead>
        <tr>
            <th>Son Pey Zamanı</th>
            <th>Üye Kullanıcı Adı</th>
            <th>Üye Adı Soyadı</th>
            <th>Telefon</th>
        </tr>
    </thead>
    <tbody>
        <?php $counter1=-1; if( isset($data["data"]) && is_array($data["data"]) && sizeof($data["data"]) ) foreach( $data["data"] as $key1 => $value1 ){ $counter1++; ?>

        <tr>
            <td><?php echo $value1["create_date"];?></td>
            <td><?php echo $value1["username"];?></td>
            <td><?php echo $value1["name"];?> <?php echo $value1["surname"];?></td>
            <td><?php echo $value1["phone"];?></td>
        </tr>
        <?php } ?>

    </tbody>
</table>
<?php } ?>