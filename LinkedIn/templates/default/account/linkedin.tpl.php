<div class="row">

    <div class="span10 offset1">
        <?=$this->draw('account/menu')?>
                <h1>LinkedIn</h1>
    </div>

</div>
<div class="row">
    <div class="span10 offset1">
        <form action="<?=\Idno\Core\site()->config()->getDisplayURL()?>account/linkedin/" class="form-horizontal" method="post">
            <?php
                if (empty(\Idno\Core\site()->config()->linkedin['appId'])) {
                    ?>
                    <div class="control-group">
                        <div class="controls-config">
	                       	<div class="row">
						   		<div class="span6">
                            <p>
                                This site has not been set up to connect to LinkedIn yet.
                            </p>
                            <?php

                                if (\Idno\Core\site()->session()->isAdmin()) {

                                ?>
                                    <p>
                                        To get started, <a href="<?=\Idno\Core\site()->config()->getDisplayURL()?>admin/linkedin/">click here</a>.
                                    </p>
                            <?php

                                }

                            ?>
                        </div>
                    </div>
                        </div>
                    </div>
                    <?php
                } else if (empty(\Idno\Core\site()->session()->currentUser()->linkedin)) {
            ?>
                    <div class="control-group">
                        <div class="controls-config">
	                      	<div class="row">
						   		<div class="span6">
						<p>
                                Easily share updates to LinkedIn.</p>  
                                
                                <p>
                                With LinkedIn connected, you can cross-post updates, pictures, and posts that you publish publicly on your site.
                            </p>
						   		</div>
	                      	</div>
                            
                             <div class="social span4">
                            
                            <p>
                                <a href="<?=$vars['login_url']?>" class="connect lkin">Connect LinkedIn</a>
                            </p>
                             
                        </div>
                   </div>
                 </div>
                <?php

                } else if (!\Idno\Core\site()->config()->multipleSyndicationAccounts()) {

                    ?>
                    <div class="control-group">
                        <div class="controls-config">
	                       <div class="row">
						   		<div class="span6">
                            <p>
                                Your account is currently connected to LinkedIn. Public content that you post here
                                will be shared with your LinkedIn account.
                            </p>
                            <p>
                                <input type="hidden" name="remove" value="1" />
                                <button type="submit" class="btn btn-large btn-primary">Click here to remove LinkedIn from your account.</button>
                            </p>
                        </div>
                    </div>
                        </div>
                    </div>

                <?php

                } else {

?>
                    <div class="control-group">
                        <div class="controls-config">
	                        <div class="row">
						   		<div class="span6">
                            <p>
                                You have connected the following accounts to LinkedIn:
                            </p>
                            <?php

                                if ($accounts = \Idno\Core\site()->syndication()->getServiceAccounts('linkedin')) {
                                    foreach ($accounts as $account) {

                                        ?>
                                        <p>
                                            <input type="hidden" name="remove" value="<?= $account['username'] ?>"/>
                                            <button type="submit"
                                                    class="btn btn-primary"><?= $account['name'] ?> (click to remove)</button>
                                        </p>
                                    <?php

                                    }

                                }

                            ?>
                            <p>
                                <a href="<?= $vars['login_url'] ?>" class="">Click here
                                    to connect another LinkedIn account</a>
                            </p>
                        </div>
                    </div>
                        </div>
                    </div>
<?php

                }
            ?>
            <?= \Idno\Core\site()->actions()->signForm('/account/linkedin/')?>
        </form>
    </div>
</div>