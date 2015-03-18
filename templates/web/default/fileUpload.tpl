<form method="post" action="" enctype="multipart/form-data">
<label for="file_upload">Vyberte soubor</label><input type="file" name="upload_file{if $multi}[]{/if}" id="file_upload" {$multi}>
<input type="submit" name="file_upload_submit" value="Nahrát!">
</form>
Povolené přípony: {$enabled_extension}
