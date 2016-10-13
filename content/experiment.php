<script>
$(document).ready(function() {
    updateExps();
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
                    <li class="active"><a href="index.php?page=experiment">Experiment</a></li>
                    <li><a href="index.php?page=project">Project</a></li>
                    <li><a href="index.php?page=configure">Configure</a></li>
                    <li><a href="index.php?page=view">View</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container">

    <h1>Select or Create an Experiment</h1>
    <p>An experiment is a unique GPR file.<br />You are encouraged to provide relevant information about the experiment in the comments field.</p>
    <div class="btn-group">
        <button type="button" data-toggle="modal" data-target="#newExp" class="btn">New Experiment</button>
        <a role button href="index.php?page=project" class="btn">Next</a>
    </div>
    <div class="modal hide fade" id="newExp"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
            <h3 id="myModalLabel">New Experiment</h3>
        </div>
        <div class="modal-body">
            <div class="alert hide fade in" id="formError">
                <p>Experiment Name and Researcher Name and a GPR file are required.</p>
            </div>
            <div id="gpr-load" class="alert hide">Loading...</div>
            <form name="exp" enctype="multipart/form-data" class="navbar-form pull-left">
                <input type="text" class="span2" name="name" /> Experiment Name<br />
                <input type="text" class="span2" name="user" /> Researcher<br />
                <textarea class="span2" name="comments"></textarea> Comments<br />
                <input type="file" class="span4" name="gpr" /> GPR File
            </form>
             
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            <button class="btn btn-primary" id="save-gpr" onclick="submitExp()">Save changes</button>
        </div>
    </div>
    
    <div class="modal hide fade" id="editExp"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
            <h3 id="myModalLabel">Edit Experiment</h3>
        </div>
        <div class="modal-body">
            <div class="alert hide fade in" id="formError">
                <p>Experiment Name and Researcher Name and a GPR file are required.</p>
            </div>
            <div id="gpr-load" class="alert hide">Loading...</div>
            <form name="exp_edit" enctype="multipart/form-data" class="navbar-form pull-left">
                <input type="text" class="span2" name="name" /> Experiment Name<br />
                <input type="text" class="span2" name="user" /> Researcher<br />
                <textarea class="span2" name="comments"></textarea> Comments<br />
                
            </form>
             
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            <button class="btn" data-dismiss="modal" aria-hidden="true">Delete</button>
            <button class="btn btn-primary" id="save-gpr" onclick="submitExp()">Save changes</button>
        </div>
    </div>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Researcher</th>
                <th>Date</th>
                <th>Comments</th>
                <th>Number of rows</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody id="exp_table">
            
        </tbody>
    </table>
</div>