<?php
$enabled = true;
$savebookmarks = true;
if (!empty(\Idno\Core\site()->config()->waybackmachine)) {
    $enabled = \Idno\Core\site()->config()->waybackmachine['enabled'];
    $savebookmarks = \Idno\Core\site()->config()->waybackmachine['savebookmarks'];
}
?>

<div class="row">

    <div class="col-md-10 col-md-offset-1">
	<?= $this->draw('admin/menu') ?>
        <h1>Wayback Machine configuration</h1>
    </div>

</div>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <form action="<?= \Idno\Core\site()->config()->getURL() ?>admin/waybackmachine/" class="form-horizontal" method="post">
            <div class="controls-group">
                <div class="controls-config">
                    <p>
                        When you create or update a post, or bookmark a link, you can use this to save the content to archive.org.</p>


                </div>
            </div>

	    <div class="controls-group">

                <p><label class="control-label" for="enabled">Save new posts to Archive.org</label><br/>
		    <input type="checkbox" id="enabled" placeholder="Enable archive.org" class="form-control"  name="enabled" value="true" <?= $enabled ? 'checked' : ''; ?> ></p>


		<p><label class="control-label" for="name">Save bookmarked links</label><br/>

		    <input type="checkbox" id="savebookmarks" placeholder="Save bookmarks" class="form-control" name="savebookmarks" value="true" <?= $savebookmarks ? 'checked' : ''; ?> ></p>


            </div>



            <div class="controls-group">
                <div class="controls-save">
                    <button type="submit" class="btn btn-primary">Save settings</button>
                </div>
            </div>
	    <?= \Idno\Core\site()->actions()->signForm('/admin/waybackmachine/') ?>
        </form>
    </div>
</div>
