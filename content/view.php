<script>
$(document).ready(function() {
    updateResults();
});
</script>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="index.php?page=configure">Microarray Analysis</a>
            <div class="nav-collapse collapse">
                <ul class="nav">

                    <li><a href="index.php?page=experiment">Experiment</a></li>
                    <li><a href="index.php?page=project">Project</a></li>
                    <li><a href="index.php?page=configure">Configure</a></li>
                    <li class="active"><a href="index.php?page=view">View</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container">

    <h1>View Results</h1>
    <p>The table can be sorted by clicking on the column headers, or downloaded into an Excel document.</p>
    <div class="btn-group">
        <button type="button" class="btn" onclick="sendExcel()">Download Excel</button>
        <a role="button" href="index.php?page=configure" class="btn">Back</a>
    </div>
    <div class="progress progress-striped" id="loading">
    <div id="progress_bar" class="bar" style="width: 20%;"></div>
    </div>
    <table class="table" id="results" style="table-layout: fixed;">
        
    </table>


</div>