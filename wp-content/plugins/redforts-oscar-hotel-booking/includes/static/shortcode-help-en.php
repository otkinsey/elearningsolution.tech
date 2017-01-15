<h1>Shortcodes</h1>
<p>
    <strong>¡Important!</strong> You may create all your custom shortcodes in the <a href="https://oscar.redforts.com/setup,be,integration,wordpress" target="_blank">integration page</a> in a visual and automatic way.
</p>

<p>
    The simplest widget offers the guest the possibility to select the
    arrival and departure date and launch a search by clicking on the
    button.
</p>

<p>
    To create this widget on your website you only need to insert the
    following shortcode to your webpage:
</p>

<pre>
[rdf-booking-widget]
</pre>

<div class="demo">
    <?php echo do_shortcode('[rdf-booking-widget]') ?>
</div>

<p>
    You can change the default text of the button by adding the text tag «button_txt=».<br>
    For example:
</p>

<pre>
[rdf-booking-widget button_txt="Check availability"]
</pre>

<div class="demo">
    <?php echo do_shortcode('[rdf-booking-widget button_txt="Check availability"]') ?>
</div>

<p>
    You can add a promotional code field by adding:
</p>

<pre>
[rdf-booking-widget promo_field="on"]
</pre>

<div class="demo">
    <?php echo do_shortcode('[rdf-booking-widget promo_field="on"]') ?>
</div>

<p>
    You can also add the widget to the pages that show a specific
    accommodation and let the result show only results for the shown
    accommodation. This can be done by adding the «acco=» to the
    shortcode you can find these codes on the <a href="https://oscar.redforts.com/setup,be,integration,wordpress" target="_blank">integraion page</a>.

    Let's assume the id of the accommodation is 1, the shortcode would
    then be:
</p>

<pre>
[rdf-booking-widget acco="1"]
</pre>

<p>You can add as many tags to the shortcode as you like, for example:</p>

<pre>
[rdf-booking-widget button_txt="Reserva esta habitación" acco="1"]
</pre>

<p>
    You can even preselect rates. Find the codes on the
<a href="https://oscar.redforts.com/setup,be,integration,wordpress" target="_blank">integration page</a> and add them to the shortcode:
</p>

<pre>
[rdf-booking-widget rate="3"]
</pre>

<p>Do not forget that you can combine all options:</p>
<pre>
[rdf-booking-widget acco="1" rate="3"]
</pre>

<p>
    For more inforamtion you can visit the
    <a href="https://oscar.redforts.com/setup,be,integration,wordpress " target="_blank">integration page</a>
    in Oscar and create automatically the shortcodes that you need.
</p>
