<?php
/*
  $Id: category_tree.php,v 1.1 2004/02/16 06:39:57 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  class osC_CategoryTree {
    var $root_category_id = 0,
        $max_level = 0,
        $data = array(),
        $root_start_string = '',
        $root_end_string = '',
        $parent_start_string = '',
        $parent_end_string = '',
        $parent_group_start_string = '<ul>',
        $parent_group_end_string = '</ul>',
        $child_start_string = '<li>',
        $child_end_string = '</li>',
        $breadcrumb_separator = '_',
        $breadcrumb_usage = true,
        $spacer_string = '',
        $spacer_multiplier = 1,
        $follow_cpath = false,
        $cpath_array = array(),
        $cpath_start_string = '',
        $cpath_end_string = '',
        $show_category_product_count = false,
        $category_product_count_start_string = '&nbsp;(',
        $category_product_count_end_string = ')';

    function osC_CategoryTree($load_from_database = true) {
      global $osC_Database, $osC_Session, $osC_Cache;

      if (SHOW_COUNTS == 'true') {
        $this->show_category_product_count = true;
      }

      if ($load_from_database === true) {
        if ($osC_Cache->read('category_tree-' . $osC_Session->value('language'), 720)) {
          $this->data = $osC_Cache->getCache();
        } else {
          $Qcategories = $osC_Database->query('select c.categories_id, cd.categories_name, c.parent_id from :table_categories c, :table_categories_description cd where c.categories_id = cd.categories_id and cd.language_id = :language_id order by c.parent_id, c.sort_order, cd.categories_name');
          $Qcategories->bindRaw(':table_categories', TABLE_CATEGORIES);
          $Qcategories->bindRaw(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
          $Qcategories->bindInt(':language_id', $osC_Session->value('languages_id'));
          $Qcategories->execute();

          $this->data = array();

          while ($Qcategories->next()) {
            $this->data[$Qcategories->valueInt('parent_id')][$Qcategories->valueInt('categories_id')] = array('name' => $Qcategories->value('categories_name'), 'count' => 0);
          }

          $Qcategories->freeResult();

          if ($this->show_category_product_count === true) {
            $this->calculateCategoryProductCount();
          }

          $osC_Cache->writeBuffer($this->data);
        }
      }
    }

    function setData(&$data_array) {
      if (is_array($data_array)) {
        $this->data = array();

        for ($i=0, $n=sizeof($data_array); $i<$n; $i++) {
          $this->data[$data_array[$i]['parent_id']][$data_array[$i]['categories_id']] = array('name' => $data_array[$i]['categories_name'], 'count' => $data_array[$i]['categories_count']);
        }
      }
    }

    function buildBranch($parent_id, $level = 0) {
      $result = $this->parent_group_start_string;

      if (isset($this->data[$parent_id])) {
        foreach ($this->data[$parent_id] as $category_id => $category) {
          if ($this->breadcrumb_usage == true) {
            $category_link = $this->buildBreadcrumb($category_id);
          } else {
            $category_link = $category_id;
          }

          $result .= $this->child_start_string;

          if (isset($this->data[$category_id])) {
            $result .= $this->parent_start_string;
          }

          if ($level == 0) {
            $result .= $this->root_start_string;
          }

          $result .= str_repeat($this->spacer_string, $this->spacer_multiplier * $level) . '<a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $category_link) . '">';
          if ($this->follow_cpath === true) {
            if (in_array($category_id, $this->cpath_array)) {
              $result .= $this->cpath_start_string . $category['name'] . $this->cpath_end_string;
            } else {
              $result .= $category['name'];
            }
          } else {
            $result .= $category['name'];
          }
          $result .= '</a>';

          if ($this->show_category_product_count === true) {
            $result .= $this->category_product_count_start_string . $category['count'] . $this->category_product_count_end_string;
          }

          if ($level == 0) {
            $result .= $this->root_end_string;
          }

          if (isset($this->data[$category_id])) {
            $result .= $this->parent_end_string;
          }

          $result .= $this->child_end_string;

          if (isset($this->data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1))) {
            if ($this->follow_cpath === true) {
              if (in_array($category_id, $this->cpath_array)) {
                $result .= $this->buildBranch($category_id, $level+1);
              }
            } else {
              $result .= $this->buildBranch($category_id, $level+1);
            }
          }
        }
      }

      $result .= $this->parent_group_end_string;

      return $result;
    }

    function buildBreadcrumb($category_id, $level = 0) {
      $breadcrumb = '';

      foreach ($this->data as $parent => $categories) {
        foreach ($categories as $id => $info) {
          if ($id == $category_id) {
            if ($level < 1) {
              $breadcrumb = $id;
            } else {
              $breadcrumb = $id . $this->breadcrumb_separator . $breadcrumb;
            }

            if ($parent != $this->root_category_id) {
              $breadcrumb = $this->buildBreadcrumb($parent, $level+1) . $breadcrumb;
            }
          }
        }
      }

      return $breadcrumb;
    }

    function buildTree() {
      return $this->buildBranch($this->root_category_id);
    }

    function calculateCategoryProductCount() {
      foreach ($this->data as $parent => $categories) {
        foreach ($categories as $id => $info) {
          $this->data[$parent][$id]['count'] = $this->countCategoryProducts($id);

          $parent_category = $parent;
          while ($parent_category != $this->root_category_id) {
            foreach ($this->data as $parent_parent => $parent_categories) {
              foreach ($parent_categories as $parent_category_id => $parent_category_info) {
                if ($parent_category_id == $parent_category) {
                  $this->data[$parent_parent][$parent_category_id]['count'] += $this->data[$parent][$id]['count'];

                  $parent_category = $parent_parent;
                  break 2;
                }
              }
            }
          }
        }
      }
    }

    function countCategoryProducts($category_id) {
      global $osC_Database;

      $Qcategories = $osC_Database->query('select count(*) as total from :table_products p, :table_products_to_categories p2c where p2c.categories_id = :categories_id and p2c.products_id = p.products_id and p.products_status = 1');
      $Qcategories->bindRaw(':table_products', TABLE_PRODUCTS);
      $Qcategories->bindRaw(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
      $Qcategories->bindInt(':categories_id', $category_id);
      $Qcategories->execute();

      $count = $Qcategories->valueInt('total');

      $Qcategories->freeResult();

      return $count;
    }

    function setRootCategoryID($root_category_id) {
      $this->root_category_id = $root_category_id;
    }

    function setMaximumLevel($max_level) {
      $this->max_level = $max_level;
    }

    function setRootString($root_start_string, $root_end_string) {
      $this->root_start_string = $root_start_string;
      $this->root_end_string = $root_end_string;
    }

    function setParentString($parent_start_string, $parent_end_string) {
      $this->parent_start_string = $parent_start_string;
      $this->parent_end_string = $parent_end_string;
    }

    function setParentGroupString($parent_group_start_string, $parent_group_end_string) {
      $this->parent_group_start_string = $parent_group_start_string;
      $this->parent_group_end_string = $parent_group_end_string;
    }

    function setChildString($child_start_string, $child_end_string) {
      $this->child_start_string = $child_start_string;
      $this->child_end_string = $child_end_string;
    }

    function setBreadcrumbSeparator($breadcrumb_separator) {
      $this->breadcrumb_separator = $breadcrumb_separator;
    }

    function setBreadcrumbUsage($breadcrumb_usage) {
      if ($breadcrumb_usage === true) {
        $this->breadcrumb_usage = true;
      } else {
        $this->breadcrumb_usage = false;
      }
    }

    function setSpacerString($spacer_string, $spacer_multiplier = 2) {
      $this->spacer_string = $spacer_string;
      $this->spacer_multiplier = $spacer_multiplier;
    }

    function setCategoryPath($cpath, $cpath_start_string = '', $cpath_end_string = '') {
      $this->follow_cpath = true;
      $this->cpath_array = explode($this->breadcrumb_separator, $cpath);
      $this->cpath_start_string = $cpath_start_string;
      $this->cpath_end_string = $cpath_end_string;
    }

    function setFollowCategoryPath($follow_cpath) {
      if ($follow_cpath === true) {
        $this->follow_cpath = true;
      } else {
        $this->follow_cpath = false;
      }
    }

    function setCategoryPathString($cpath_start_string, $cpath_end_string) {
      $this->cpath_start_string = $cpath_start_string;
      $this->cpath_end_string = $cpath_end_string;
    }

    function setShowCategoryProductCount($show_category_product_count) {
      if ($show_category_product_count === true) {
        $this->show_category_product_count = true;
      } else {
        $this->show_category_product_count = false;
      }
    }

    function setCategoryProductCountString($category_product_count_start_string, $category_product_count_end_string) {
      $this->category_product_count_start_string = $category_product_count_start_string;
      $this->category_product_count_end_string = $category_product_count_end_string;
    }
  }
?>
