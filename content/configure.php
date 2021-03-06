<script>
$(document).ready(function() {
    filters = new Array();
    outputs = new Array();
    exps = {};
    updateConExps();
    getColumns();
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
            <a class="brand" href="index.php?page=project">Microarray Analysis</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li><a href="index.php?page=experiment">Experiment</a></li>
                    <li><a href="index.php?page=project">Project</a></li>
                    <li class="active"><a href="index.php?page=configure">Configure</a></li>
                    <li><a href="index.php?page=view">View</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container">

    <h1>Setup Analysis</h1>
    <p>Specify the GPR files to use, filters, and settings to analyze.</p>
    <div class="btn-group">
        <button class="btn">Reset</button>
        <a href="index.php?page=view" class="btn">Next</a>
    </div>
    <div>
	
        <legend>Select experiments to analyze</legend>
	<div class="well pre-scrollable">
	<table class="table table-striped" style="width: 200px">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Unused</th>
                    <th>Mutant</th>
                    <th>Control</th>

                </tr>
            </thead>
            <tbody id="con_exp_table">

            </tbody>
	</table>
	</div>
        <label type="checkbox">
            <input id="normalize" type="checkbox" checked> Normalize
        </label>
        
        
            <div class="span6">
                <label>Add Filters</label>
                <div class="input-append">
                    <select class="span2" id="filters"></select> 
                    <select class="span2" id="relationship">
                        <option>></option>
                        <option><</option>
                        <option>=</option>
                        <option>>=</option>
                        <option><=</option>
                        <option>!=</option>
                        <option>Std. Dev. ></option>
                    </select>
                    <input type="text" id="filter_value" class="span2" />
                    <button class="btn" onclick="addFilter()"><i class="icon-plus-sign"></i></button>
                </div>
                <div id="filter_div" class="well well-small clearfix"></div>
            </div>
            <div class="span6">
                <label>Output</label>
                <div class="input-append">
                    <label type="checkbox">
            <input id="ttest" type="checkbox" checked> T-Test
        </label>
                    <select class="span2" id="stat">
                        <option value="none">None</option>
                        <option value="mean">Mean</option>
                        <option value="median">Median</option>
                        <option value="std_dev">Standard Deviation</option>
                        <option value="var">Variance</option>
                        <option value="min">Minimum</option>
                        <option value="max">Maximum</option>

                    </select> 
                    <select class="span2" id="outputs">
                    </select>

                    

                    <button class="btn" onclick="addOutput()"><i class="icon-plus-sign"></i></button>
                </div>
                <div id="output_div" class="well well-small"></div>
            </div>
        

    </div>
</div>
