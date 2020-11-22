<div class="container">
    <div class="row ">
        <div class="col-12 ">
            <h1 class="text-center">Welcome!</h1>
            <p class="text-center">Input country and city names in English</p>
        </div>
    </div>
    <?php if (!empty($validationErrors)) { ?>
        <div class="border border-danger pt-3 pb-2 mb-5">
            <ul class="fa-ul">
                <?php foreach ( $validationErrors as $error ) { ?>
                    <li class="text-danger"><span class="fa-li"><i class="fas fa-exclamation-circle"></i></span><?= esc($error) ?></li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>
    <?= form_open(route_to('home.getWeather')) ?>
        <div class="row">
            <div class="col-12 col-md-6 col-xl-3 offset-xl-3">
                <div class="form-group">
                    <label for="country">Country</label>
                    <input name="country" type="text" class="form-control" id="country" placeholder="Country" required>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="form-group">
                    <label for="city">City</label>
                    <input name="city" type="text" class="form-control" id="city" placeholder="City" required>
                </div>
            </div>
            <div class="col-12 col-md-6 offset-md-3 col-xl-4 offset-xl-4 text-center">
                <button type="submit" class="btn btn-success">Check temperature</button>
            </div>

        </div>
    <?= form_close() ?>

</div>