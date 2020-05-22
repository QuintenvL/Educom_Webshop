<?php
include_once 'productCardDoc.php';
class ProductTopDoc extends ProductCardDoc{
    private $rankingCounter;
    public function __construct($model){
        parent::__construct($model);
    }
    /**
     * Show the content of the product top page. 
     * 
     * {@inheritDoc}
     * @see BaseDoc::middleMainContent()
     */
    protected function middleMainContent(){
        $this->htmlElement('h3', 'The ' . PRODUCT_TOP_LIMIT . ' most ordered products', 'text-center');
        $this->timeIntervalHeading();
        $this->productsContent();
    }
    /**
     * Show the body of a product card.
     * This is different content than set in productCardDoc.
     * It contains the ranking, a link (name and image), and the amount it has been ordered.
     * 
     * {@inheritDoc}
     * @see ProductCardDoc::bodyProductCard()
     */
    protected function bodyProductCard(){
        $this->htmlElementStart('div', 'card-body text-center');
            $this->htmlElement('span', $this->product->ranking, 'lead mr-4');
            $this->link('text-dark text-decoration-none', 'top card-img-top img-fluid mt-2');
            $this->htmlElement('span', $this->product->total_amount.' time(s) ordered', 'badge text-wrap lead');
        $this->htmlElementEnd('div');
    }
    /**
     * Show the middle of the products content.
     * This differs from the content set in ProductsDoc.
     * It goes over all teh products and puts it in a div with column specifications.
     * It also keeps track of the ranking of the product.
     * 
     * {@inheritDoc}
     * @see ProductsDoc::middleProducts()
     */
    protected function middleProducts(){
        foreach ($this->model->products as $product){
            $this->htmlElementStart('div', 'col-md-6 col-sm-10');
                $this->productCard($product);
            $this->htmlElementEnd('div');
        }
    }
    /**
     * Show the start of the products content.
     * In this case the cards are shown in columns instead of groups.
     * 
     * {@inheritDoc}
     * @see ProductsDoc::startProducts()
     */
    protected function startProducts(){
        $this->htmlElementStart('div', 'row card-columns justify-content-center');
    }
    /**
     * Show the time interval heading.
     * It shows the time interval from today to the set interval in constants.php.
     * 
     */
    private function timeIntervalHeading(){
        $today = strtotime('today');
        $lastPeriod = date('d M Y', strtotime(TOP_TIME_INTERVAL, $today));
        $today = date('d M Y', $today);
        $this->htmlElement('h5', '('.$lastPeriod. ' --- '. $today.')', 'text-center text-secondary'); 
    }
}

?>