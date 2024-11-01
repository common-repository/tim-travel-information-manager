<dl class="tim-dl">
    <dt>
        <?php _e( 'Check-in', $plugin_name ); ?>
    </dt>
    <dd>
        <b><?php echo $this->format_date( $item->start_date, '', $content_language ); ?></b>
    </dd>
    <dt>
        <?php _e( 'Check-out', $plugin_name ); ?>
    </dt>
    <dd>
        <b><?php echo $this->format_date( $item->end_date, '', $content_language ); ?></b> <small>(<?php echo $item->total_nights; ?> <?php echo $labelNights; ?>)</small>
    </dd>
    <dt>
        <?php _e( 'Total pax', $plugin_name ); ?>
    </dt>
    <dd>
        <?php echo $totalPax; ?>
    </dd>
    <dt>
        <?php _e( 'Rooms', $plugin_name ); ?>
    </dt>
    <dd>
        <b><?php echo $item->total_rooms; ?></b> <?php echo $label_rooms; ?>
        <?php
        foreach ( $item->booked_rooms as $room ) {
            echo '<em><br /> &nbsp; &nbsp; - '. $room->name .' (x'. count($room->units) .')</em>';

            $i++;
        }


        /*$i = 1;
        foreach ( $roomTypesSelected as $room ) {
            $coma = ( $i < count($roomTypesSelected) ) ? ', ' : '';

            echo '<em><br /> &nbsp; &nbsp; - '. $room->name .' (x'. $room->unitsPerRoom .')</em>'. $xcoma;

            $i++;
        }*/
        ?>
    </dd>
</dl>