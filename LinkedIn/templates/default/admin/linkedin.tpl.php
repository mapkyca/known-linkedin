<div class="row">

    <div class="span10 offset1">
        <h1>LinkedIn</h1>
        <?=$this->draw('admin/menu')?>
    </div>

</div>
<div class="row">
    <div class="span10 offset1">
        <form action="/admin/linkedin/" class="form-horizontal" method="post">
            <div class="control-group">
                <div class="controls">
                    <p>
                        To begin using LinkedIn, <a href="https://www.linkedin.com/secure/developer" target="_blank">create a new application in
                            the LinkedIn apps portal</a>.</p>
                    <p>
                        Add the following URL to the OAuth2 callback url box <strong><?=\Idno\Core\site()->config()->url?>linkedin/callback</strong>.
                    </p>
                    <p>
                        Once you've finished, fill in the details below:
                    </p>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="name">App Key</label>
                <div class="controls">
                    <input type="text" id="name" placeholder="App Key" class="span4" name="appId" value="<?=htmlspecialchars(\Idno\Core\site()->config()->linkedin['appId'])?>" >
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="name">Secret Key</label>
                <div class="controls">
                    <input type="text" id="name" placeholder="Secret Key" class="span4" name="secret" value="<?=htmlspecialchars(\Idno\Core\site()->config()->linkedin['secret'])?>" >
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
            <?= \Idno\Core\site()->actions()->signForm('/admin/linkedin/')?>
        </form>
    </div>
</div>