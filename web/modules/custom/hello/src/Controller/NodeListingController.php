<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class NodeListingController extends ControllerBase {

  public function content($nodeType = NULL) {
    $nodes_types = $this->entityTypeManager()->getStorage('node_type')->loadMultiple();

    $node_type_items = [];
    foreach ($nodes_types as $node_type) {
      $url = new Url('hello.node', ['nodetype' => $node_type->id()]);
      $node_type_link = new Link($node_type->label(), $url);
      $node_type_items[] = $node_type_link;
    }

    $node_type_list = [
      '#theme' => 'item_list',
      '#items' => $node_type_items,
      '#title' => $this->t('Filter by node types'),
    ];

    // permet la manipulation des noeuds
    $nodes_storage = $this->entityTypeManager()->getStorage('node');
    // permet de faire des requêtes sur les noeuds
    $query= $nodes_storage->getQuery();
    // filtre argument url
    if ($nodeType) {
      $query->condition('type', $nodeType);
    }
    // récupères les ids des noeuds
    $nids = $query->pager(5)->execute();
    // récupères les noeuds
    $nodes = $nodes_storage->loadMultiple($nids);
    // créer un tableaux de liens des noeuds
    $items = [];
    foreach ($nodes as $node) {
      $items[] = $node->toLink();
    }
    // liste des liens
    $list = [
      '#theme' => 'item_list',
      '#items' => $items,
      '#title' => $this->t('Node list'),
    ];
    // paginaion
    $pager = ['#type' => 'pager'];

    return [
      'node_list_type' => $node_type_list,
      'list' => $list,
      'pager' => $pager,
      '#cache' => ['max-age' => '0'],
    ];
  }

}
