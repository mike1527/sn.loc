<?php
class ControllerCommonSearch extends Controller
{
    public function index()
    {
        $this->load->language('common/search');

        $data['text_search'] = $this->language->get('text_search');

        if (isset($this->request->get['search'])) {
            $data['search'] = $this->request->get['search'];
        } else {
            $data['search'] = '';
        }

        return $this->load->view('common/search', $data);
    }

    public function autocomplete()
    {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('catalog/product');

            $filter_data = array(
                'filter_name' => $this->request->get['filter_name'],
                'start' => 0,
                'limit' => 5
            );

            $results = $this->model_catalog_product->getProducts($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'link' => str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $result['product_id']))
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}

