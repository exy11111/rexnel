<div class="modal fade" id="viewAllModal" tabindex="-1" aria-labelledby="viewAllModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAllModalLabel">All Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php foreach ($topProducts as $row): ?>
                    <div class="card mb-3">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-1"><?php echo $row['item_name'];?></h5>
                                <p class="mb-1">Price: ₱<?php echo number_format($row['price'], 2);?></p>
                            </div>
                            <div class="flex-grow-1 text-center">
                                <p class="mb-0 fw-bold">Sold: <?php echo $row['total_sold'];?></p>
                            </div>
                            <a href="stock.php" class="btn btn-primary btn-sm">View</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>