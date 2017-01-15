<h1>Shortcodes</h1>
<p>
    <strong>¡Atención!</strong> Puede crear todos sus shortcodes personalizados en la <a href="https://oscar.redforts.com/setup,be,integration,wordpress" target="_blank">página de integración</a> de Oscar de forma automática y visual.
</p>

<p>
    El widget más sencillo permite al cliente seleccionar las fechas de llegada
    y salida y lanzar la búsqueda pinchando en un botón.
</p>

<p>
    Para crear este widget en tu página solo necesitas editar la página y añadir
    el siguiente shortcode:
</p>

<pre>
[rdf-booking-widget]
</pre>

<div class="demo">
    <?php echo do_shortcode('[rdf-booking-widget]') ?>
</div>

<p>
    Puedes cambiar el texto del botón añadiendo un texto al tag «button_txt=».<br>
    Por ejemplo:
</p>

<pre>
[rdf-booking-widget button_txt="Comprobar disponibilidad"]
</pre>

<div class="demo">
    <?php echo do_shortcode('[rdf-booking-widget button_txt="Comprobar disponibilidad"]') ?>
</div>

<p>
    Puede hacer que aparezca el campo para que el usuario pueda agregar un código promocional.
</p>

<pre>
[rdf-booking-widget promo_field="on"]
</pre>

<div class="demo">
    <?php echo do_shortcode('[rdf-booking-widget promo_field="on"]') ?>
</div>

<p>
    También puede añadir el widget a las páginas que muestran un alojamiento
    específico y así permitir el cliente reservar el alojamiento mostrado. Esto se
    consigue añadiendo el tag «acco=» al shortcode. Puede encontrar los ids de los
    alojamientos en la <a href="https://oscar.redforts.com/setup,be,integration,wordpress" target="_blank">página de integración</a>.<br>
    Supongamos que el id del alojamiento es 1, el shortcode sería:
</p>

<pre>
[rdf-booking-widget acco="1"]
</pre>

<p>Puede añadir tantas tags al shortcode que quiere como por ejemplo:</p>

<pre>
[rdf-booking-widget button_txt="Reserva esta habitación" acco="1"]
</pre>

<p>
    Hasta puede pre-seleccionar tarifas. Busca los códigos de las tarifas en
    Oscar, y añádelos al shortcode:
</p>

<pre>
[rdf-booking-widget rate="3"]
</pre>

<p>No olvide que puedes combinar todas las opciones:</p>
<pre>
[rdf-booking-widget acco="1" rate="3"]
</pre>

<p>
    Si tienes dudas sobre los códigos visita la <a href="https://oscar.redforts.com/setup,be,integration,wordpress" target="_blank">página de integración</a> en Oscar, donde podrás crear automáticamente los shortcodes que necesites.
</p>
