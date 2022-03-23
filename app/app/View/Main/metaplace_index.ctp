    <?php
          echo $this->Html->image(
                  '/img/metaplace-home-placeholder.jpg', 
                  array(
                      "title" => __('Plataforma Metaplace'), 
                      "height" => "320px;", 
                      'title'=> __('Plataforma Metaplace'),
                  )
              );
    ?>
    <section id="metaplace_menu">
        <nav>
            <ul>
                <?php if (empty($uid)): ?>
              <li>
                <button type="button" onclick="window.location.href='<?php 
                    echo Router::url(array('controller'=>'users', 'action'=>'login'))
                    ?>'">
                    <?php echo __("Já tenho uma conta e quero fazer login"); ?>
                </button>
              </li>
              <li>
                <button type="button" onclick="window.location.href='<?php 
                    echo Router::url(array('controller'=>'curators', 'action'=>'register'))
                    ?>'">
                    <?php echo __("Quero criar uma conta"); ?>
                </button>
              </li>
                <?php else: ?>
              <li>
                <button type="button" onclick="window.location.href='<?php 
                    echo Router::url(array('controller'=>'users', 'action'=>'profile', $uid))
                    ?>'">
                    <?php echo __("Ir para minha página"); ?>
                </button>
              </li>
                <?php endif; ?>
              <li>
                <button type="button" onclick="window.location.href='<?php 
                    echo Router::url(array('controller'=>'main', 'action'=>'marketplaces'))
                    ?>'">
                    <?php echo __("Ver marketplaces publicados"); ?>
                </button>
              </li>
            </ul>
          </nav>
    </section>