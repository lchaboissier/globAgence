<?php include ('../page/template/header.php'); ?>

<div class="card-body">
    <form class="form-horizontal" action=".?route=feature&action=addExample" method="POST">
        <div class="row">
            <div class="col-sm-10">
                <div class="form-group">
                    <label for="ref" class="col-sm-2 col-form-label">Nom</label>
                    <div class="col-sm-5">
                        <div class="fx-relay-email-input-wrapper">
                            <input type="text" name="name" class="form-control">
                        
                        </div>
                    </div>
                    
                </div>
            </div>
            <button class="col-sm-2 btn btn-default" type="submit"><i class="fas fa-plus"></i></button>
        </div>
    </form>

    <table id="example" class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>#</th>
        <th>Nom</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach($examples as $example){
        echo '<tr>';
        echo '<td>'.$example->getId().'</td>';
        echo '<td>'.$example->getName().'</td>';
        echo '</tr>';
    }
    ?>
    </tbody>
</table>

</div>

<?php include ('../page/template/footer.php'); ?>