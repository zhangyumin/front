<style>
    .row{
        border: #bce8f1 1px solid;
        border-radius: 5px;
        font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif;
    }
    .panel-title{
        color:#5499c9;
        padding-left: 10px;
        margin-top: 0;
        margin-bottom: 0;
        font-size: 18px;
    }
    .panel-heading{
        background-color: #bce8f1;
        padding: 10px 15px;
    }
    .panel-body{
        padding: 15px;
        font-size: 16px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
          <div class="panel-heading" id="summaryHeading">
              <h3 class="panel-title">Localization</h3>
          </div>
          <div class="panel-body">
            <p>Localization allows you to detect macromolecules in cryo-electron tomograms as first described in <a href="http://www.pnas.org/content/99/22/14153.short" target="#blank">(Frangakis et. al. 2002)</a>. This approach in our <a href="http://www.pytom.org" target="#blank">PyTom</a> implemenatation is currently the most efficient and versatile in performance. The server implementation here allows the user to run localization using the <a href="http://pytom.org/doc/epydoc/pytom.score.score.FLCFScore-class.html" target="#blank">FLCF score</a> using localy normalized cross-correlation adapted to the missing wedge problem. PyTom contains additional scores, but FLCF has proved to be most reliable. </p>
            <p>Results of this server will be density-volumes of candidate macromolecules stored in volume files and a list of the "Particle" coordinates stored in XML format. The result webpage furthermore allows you to analyze the particles list in more detail and interactively generate molecular averages of sub-sets of particles based on the score distribution. A novel tool for browser-based 3D visualization has been implemented on the pages and is similar to density analysis in the <a href="http://www.cgl.ucsf.edu/chimera/" target="#blank">Chimera</a> package.</p>
          </div>
        </div>
    </div>
</div>