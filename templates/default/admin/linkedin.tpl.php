<div class="row">

    <div class="col-md-10 col-md-offset-1">
	            <?=$this->draw('admin/menu')?>
        <h1>LinkedIn configuration</h1>
    </div>

</div>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <form action="<?=\Idno\Core\site()->config()->getURL()?>admin/linkedin/" class="form-horizontal" method="post">
            <div class="controls-group">
                <div class="controls-config">
                    <p>
                        To begin using LinkedIn, <a href="https://www.linkedin.com/secure/developer" target="_blank">create a new application in
                            the LinkedIn apps portal</a>.</p>
                            
                    <p>
                        Set the OAuth2 callback url to:<br />
                        <input type="text" class="form-control" value="<?=\Idno\Core\site()->config()->url?>linkedin/callback" />
                    </p>
                    
                </div>
            </div>

           <div class="controls-group">
	                                <p>
                        Once you've finished, fill in the details below:
                    </p>
                <label class="control-label" for="name">API Key</label>

                    <input type="text" id="name" placeholder="API Key" class="form-control" name="appId" value="<?=htmlspecialchars(\Idno\Core\site()->config()->linkedin['appId'])?>" >


                 <label class="control-label" for="name">Secret Key</label>

                    <input type="text" id="name" placeholder="Secret Key" class="form-control" name="secret" value="<?=htmlspecialchars(\Idno\Core\site()->config()->linkedin['secret'])?>" >


            </div>
            
                      <div class="controls-group">
	          <p>
                        After the LinkedIn application is configured, <a href="<?=\Idno\Core\site()->config()->getDisplayURL()?>account/linkedin">click here to authenticate with LinkedIn</a>.
                    </p>

          </div>  


            <div class="controls-group">
                <div class="controls-save">
                    <button type="submit" class="btn btn-primary">Save settings</button>
                </div>
            </div>
            <?= \Idno\Core\site()->actions()->signForm('/admin/linkedin/')?>
        </form>
    </div>
</div>