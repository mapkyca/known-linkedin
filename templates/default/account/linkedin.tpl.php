<div class="row">

    <div class="col-md-10 col-md-offset-1">
        <?= $this->draw('account/menu') ?>
        <h1>LinkedIn</h1>
    </div>

</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <?php
            if (empty(\Idno\Core\site()->config()->linkedin['appId'])) {
                ?>
                <div class="control-group">
                    <div class="controls-config">
                        <div class="row">
                            <div class="col-md-7">
                                <p>
                                    This site has not been set up to connect to LinkedIn yet.
                                </p>
                                <?php

                                    if (\Idno\Core\site()->session()->isAdmin()) {

                                        ?>
                                        <p>
                                            To get started, <a
                                                href="<?= \Idno\Core\site()->config()->getDisplayURL() ?>admin/linkedin/">create
                                                a LinkedIn app</a>.
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
                            <div class="col-md-7">
                                <p>
                                    Easily share updates to LinkedIn.</p>

                                <p>
                                    With LinkedIn connected, you can cross-post updates, pictures, and posts that you
                                    publish publicly on your site.
                                </p>


								<div class="social">

								<p>
                               	 <a href="<?= $vars['login_url'] ?>" class="connect lkin"><i class="fab fa-linkedin"></i>
 Connect LinkedIn</a>
                            	</p>
								</div>
                            </div>

                        </div>
                    </div>
                </div>
            <?php

            } else if (!\Idno\Core\site()->config()->multipleSyndicationAccounts()) {

                ?>
                <div class="control-group">
                    <div class="controls-config">
                        <div class="row">
                            <div class="col-md-7">
                                <p>
                                    Your account is currently connected to LinkedIn. Public updates, pictures, and posts
                                    that you publish here
                                    can be cross-posted to LinkedIn.
                                </p>

                                <div class="social">
                                    <form action="<?= \Idno\Core\site()->config()->getDisplayURL() ?>account/linkedin/"
                                          class="form-horizontal" method="post">
                                        <p>
                                            <input type="hidden" name="remove" value="1"/>
                                            <button type="submit" class="connect lkin connected"><i class="fab fa-linkedin"></i>
 Disconnect LinkedIn
                                            </button>
                                            <?= \Idno\Core\site()->actions()->signForm('/account/linkedin/') ?>
                                        </p>
                                    </form>
                                </div>
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
                            <div class="col-md-7">
                                <p>
                                    Your account is currently connected to LinkedIn. Public updates, pictures, and posts
                                    that you publish here
                                    can be cross-posted to LinkedIn.
                                </p>

                                <div class="social">
                                    <?php

                                        if ($accounts = \Idno\Core\site()->syndication()->getServiceAccounts('linkedin')) {
                                            foreach ($accounts as $account) {

                                                ?>
                                                <form
                                                    action="<?= \Idno\Core\site()->config()->getDisplayURL() ?>account/linkedin/"
                                                    class="form-horizontal" method="post">
                                                    <p>
                                                        <input type="hidden" name="remove"
                                                               value="<?= $account['username'] ?>"/>
                                                        <button type="submit"
                                                                class="connect lkin connected"><i class="fab fa-linkedin"></i>
 <?= $account['name'] ?>
                                                            (Disconnect)
                                                        </button>
                                                        <?= \Idno\Core\site()->actions()->signForm('/account/linkedin/') ?>
                                                    </p>
                                                </form>
                                            <?php

                                            }

                                        }

                                    ?>
                                </div>
                                <p>
                                    <a href="<?= $vars['login_url'] ?>" class="">
                                        <i class="fa fa-plus"></i>
                                        Add another LinkedIn account</a>
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
            <?php

            }
        ?>
    </div>
</div>
