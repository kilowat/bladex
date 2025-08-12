<div class="col-lg-9 col-md-9">
    <div class="row">
        @foreach ($items as $item)
            <div class="col-lg-4 col-md-6">
                <div class="product__item">
                    <div class="product__item__pic set-bg" data-setbg="{{ $item['image'] }}">
                        <div class="label new">New</div>
                        <ul class="product__hover">
                            <li><a href="img/shop/shop-1.jpg" class="image-popup"><span class="arrow_expand"></span></a>
                            </li>
                            <li><a href="#"><span class="icon_heart_alt"></span></a></li>
                            <li><a href="#"><span class="icon_bag_alt"></span></a></li>
                        </ul>
                    </div>
                    <div class="product__item__text">
                        <h6><a href="#">{{ $item['name'] }}</a></h6>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <div class="product__price">$ 59.0</div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-lg-12 text-center">
            <div class="pagination__option">
                <a href="#">1</a>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#"><i class="fa fa-angle-right"></i></a>
            </div>
        </div>
    </div>
</div>