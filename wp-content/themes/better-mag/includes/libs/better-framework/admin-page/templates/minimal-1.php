<div class="bf-admin-page-wrap bf-admin-template-minimal-1">

    <header class="bf-page-header">
        <h2 class="page-title"><?php echo $title; ?></h2>

        <?php if( ! empty( $desc ) ) echo '<div class="page-desc">' . $desc . '</div>'; ?>
    </header>

    <div class="bf-page-postbox">
        <div class="inside">

            <?php echo $body; ?>

        </div>
    </div>

</div>