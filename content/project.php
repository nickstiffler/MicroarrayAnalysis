<script>
$(document).ready(function() {
    updateProjects();
    typeaheadExps();
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
                    <li class="active"><a href="index.php?page=project">Project</a></li>
                    <li><a href="index.php?page=configure">Configure</a></li>
                    <li><a href="index.php?page=view">View</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container">

    <h1>Select or Create a Project</h1>
    <p>A project is a general way to organize multiple experiments.<br>Experiments can be associated with one or more projects. Multiple projects can be selected to analyze.</p>

    <div class="modal hide fade" id="newProj"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-target="#newProj" data-dismiss="modal" aria-hidden="true" onclick="clearNewProject()"><i class="icon-remove"></i></button>
            <h3 id="myModalLabel">New Project</h3>
        </div>
        <div class="modal-body">
            <div class="alert hide fade in" id="formError">
                <p>Project Name and Researcher Name are required.</p>
            </div>
            <div class="form-horizontal" name="project">
                <input type="text" class="span2" name="name" id="proj_name" /> Project Name<br />
                <input type="text" class="span2" name="user" id="proj_user" /> Researcher<br />
                <textarea class="span2" name="comments" id="proj_comments"></textarea> Comments<br />
                <span class="input-append"><input type="text" class="span2" style="" data-provide="typeahead" id="exps_list" autocomplete="off" /><button class="btn" onclick="addProjExp()"><i class="icon-plus-sign"></i></button></span>Add Experiment
            </div>
            <div id="new_proj_exps" class="well well-small"></div>
           
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" data-target="#newProj" aria-hidden="true" onclick="clearNewProject()">Close</button>
            <button class="btn" onclick="submitProject()">Save changes</button>
        </div>
    </div>
    
    <div class="modal hide fade" id="editProj"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-target="#editProj" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
            <h3 id="myModalLabel">Edit Project</h3>
        </div>
        <div class="modal-body">
            <div class="alert hide fade in" id="formError">
                <p>Project Name and Researcher Name are required.</p>
            </div>
            <div class="form-horizontal" name="project">
                <input type="text" class="span2" name="name" /> Project Name<br />
                <input type="text" class="span2" name="user" /> Researcher<br />
                <textarea class="span2" name="comments"></textarea> Comments<br />
                <span class="input-append"><input type="text" class="span2" style="" data-provide="typeahead" id="exps_list" autocomplete="off" /><button class="btn" onclick="addProjExp()"><i class="icon-plus-sign"></i></button></span>Add Experiment
            </div>
            <div id="proj_exps" class="well well-small"></div>
           
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" data-target="#editProj" aria-hidden="true" >Close</button>
            <button class="btn" data-dismiss="modal" data-target="#editProj" aria-hidden="true">Delete</button>
            <button class="btn" onclick="submitProject()">Save changes</button>
        </div>
    </div>
    
    
    <div class="btn-group">
        <button type="button" data-toggle="modal" data-target="#newProj" class="btn">New Project</button>
        <a role="button" href="index.php?page=configure" class="btn">Next</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Researcher</th>
                <th>Date</th>
                <th>Comments</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody id="proj_table">
            
        </tbody>
    </table>
</div>

