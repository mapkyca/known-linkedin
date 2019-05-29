<div class="row">

    <div class="col-md-10 col-md-offset-1">
	            <?=$this->draw('admin/menu')?>
        <h1><?= Idno\Core\Idno::site()->language()->_('LinkedIn configuration'); ?></h1>
    </div>

</div>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <form action="<?=\Idno\Core\site()->config()->getURL()?>admin/linkedin/" class="form-horizontal" method="post">
            <div class="controls-group">
                <div class="controls-config">
                    <p>
                        <?= Idno\Core\Idno::site()->language()->_('To begin using LinkedIn, <a href="https://www.linkedin.com/secure/developer" target="_blank">create a new application in the LinkedIn apps portal</a>.') ?></p>
                            
                    <p>
                        <?= Idno\Core\Idno::site()->language()->_('Set the OAuth2 callback url to'); ?>:<br />
                        <input type="text" class="form-control" value="<?=\Idno\Core\site()->config()->url?>linkedin/callback" />
                    </p>
                    
                </div>
            </div>

           <div class="controls-group">
	                                <p>
                        <?= Idno\Core\Idno::site()->language()->_('Once you\'ve finished, fill in the details below:'); ?>
                    </p>
                <label class="control-label" for="name">API Key</label>

                    <input type="text" id="name" placeholder="API Key" class="form-control" name="appId" value="<?=htmlspecialchars(\Idno\Core\site()->config()->linkedin['appId'])?>" >


                 <label class="control-label" for="name">Secret Key</label>

                    <input type="text" id="name" placeholder="Secret Key" class="form-control" name="secret" value="<?=htmlspecialchars(\Idno\Core\site()->config()->linkedin['secret'])?>" >


            </div>
            
                      <div class="controls-group">
	          <p>
                        <?= Idno\Core\Idno::site()->language()->_('After the LinkedIn application is configured, <a href="%saccount/linkedin">click here to authenticate with LinkedIn', [ \Idno\Core\site()->config()->getDisplayURL() ]); ?></a>.
                    </p>

          </div>  


            <div class="controls-group">
                <div class="controls-save">
                    <button type="submit" class="btn btn-primary"><?= Idno\Core\Idno::site()->language()->_('Save settings'); ?></button>
                </div>
            </div>
            <?= \Idno\Core\site()->actions()->signForm('/admin/linkedin/')?>
        </form>
    </div>
</div>