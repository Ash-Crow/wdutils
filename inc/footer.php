<?php 
$timerStop = microtime(true);
$timeSpent= $timerStop - $timerStart;
?>

   <footer class="footer">
      <div class="container">
        <p class="text-muted">Script exécuté en <?php echo round($timeSpent,2); ?> secondes.</p>
      </div>
    </footer>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="js/sorttable.js"></script>
  </body>
</html>