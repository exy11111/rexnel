<div class="modal fade" id="viewAllModal" tabindex="-1" aria-labelledby="viewAllModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAllModalLabel">All Product Sales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <?php foreach ($topProducts as $row): ?>
                    <div class="card mb-3 top-product-modal"
                        data-name="<?= htmlspecialchars($row['item_name']) ?>"
                        data-price="<?= $row['price'] ?>"
                        data-sold="<?= $row['total_sold'] ?>"
                        data-revenue="<?= $row['total_revenue'] ?>">

                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-1"><?= $row['item_name'] ?></h5>
                                <p class="mb-0">Price: ₱<?= number_format($row['price'], 2) ?></p>
                            </div>

                            <div class="flex-grow-1 text-center">
                                <p class="mb-0 fw-bold">Sold: <?= $row['total_sold'] ?></p>
                            </div>

                            <a href="stock.php" class="btn btn-primary btn-sm">View</a>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</div>
