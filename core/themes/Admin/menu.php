
<ul class="<?php echo $level === 1 ? 'nav navbar-nav' : 'dropdown-menu'; ?>">
    <?php foreach ($menu as $link): ?>

    <li class="<?php echo empty($link[ 'submenu' ]) ? '' : '​dropdown-submenu'; ?> <?php echo $link[ 'link_active' ]; ?>">
        <?php if (!empty($link[ 'submenu' ])): ?>

        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <?php echo !empty($link['icon']) ? "<i class='{$link['icon']}' aria-hidden='true'></i> " : ''; ?><?php echo $link[ 'title_link' ]; ?> <span class="caret"></span>
            
        </a>
        <?php else: ?>

        <a href="<?php echo $link[ 'link' ]; ?>" target="<?php echo $link[ 'target_link' ]; ?>" <?php if ($link[ 'target_link' ] === '_blank'): ?> rel="noopener noreferrer" <?php endif; ?>>
            <?php echo !empty($link['icon']) ? "<i class='{$link['icon']}' aria-hidden='true'></i> " : ''; ?><?php echo $link[ 'title_link' ]; ?>
            
        </a>
        <?php endif; ?>
        <?php if (!empty($link[ 'submenu' ])): ?>

            <?php echo $link[ 'submenu' ]; ?>
        <?php endif; ?>

    </li>
    <?php endforeach; ?>

</ul>