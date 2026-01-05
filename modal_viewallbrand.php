<div class="modal fade" id="viewAllBrandsModal" tabindex="-1" aria-labelledby="viewAllBrandsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAllBrandsModalLabel">All Brand Sales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <?php foreach ($topBrands as $row): ?>
                    <div class="card mb-3 top-brand-modal"
                        data-brand="<?= htmlspecialchars($row['brand_name']) ?>"
                        data-sold="<?= $row['total_sold'] ?>"
                        data-revenue="<?= $row['total_revenue'] ?>">
                        
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-1"><?= $row['brand_name'] ?></h5>
                            </div>
                            <div class="flex-grow-1 text-center">
                                <p class="mb-0 fw-bold">Sold: <?= $row['total_sold'] ?></p>
                            </div>
                            <a href="brands.php" class="btn btn-primary btn-sm">View</a>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</div>

