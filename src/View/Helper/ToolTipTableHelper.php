<?php
namespace MelisCommerce\View\Helper;

use Laminas\View\Helper\AbstractHelper;

/**
 * This class helps you create and render to a configurable HTML Table
 *
 */
class ToolTipTableHelper extends AbstractHelper
{
    
    protected $table;
    protected $columns;
    protected $rowData;
    protected $body;
    
    public function setTable($tableId, $tableClass, $style = 'width: 10px')
    {
        $this->table = '<table id="' . $tableId . '" class="tooltiptext qtipTable ' . '" style="' . $style . '">';
    }
    
    /**
     * @usage: 
     * setColumns(array(
    	    'Column 1' => array(
    	        'rowspan' => '2',
    	        'style' => 'width:20px'
    	    ),
    	    'Column 2' => array(
    	        'rowspan' => '2',
    	        'style' => 'width:20px'
    	    ),
    	));
    	The array key is the text while the child array are the attributes
     * {@inheritDoc}
     * @see \MelisCore\View\Helper\MelisGenericTable::setColumns()
     */
    public function setColumns($columns = array()) 
    {
        
        $tmpColData = '';
        if($columns) {
            foreach($columns as $colKey => $colAttrVal) {

                $tmpColData .= '<th ';
                foreach($colAttrVal as $attr => $val) {
                    $tmpColData .= $attr .'="'.$val.'" ';
                }
                
                $tmpColData .= '>'.$colKey.'</th>';
            }
        }
        
        $this->columns = $tmpColData;
        
    }
    
    /**
     * Inserts Table Row tag in a table
     */
    public function openTableRow()
    {
        return $this->rowData = '<tr>';
    }
    
    /**
     * Closes the Table Row tag in a table
     */
    public function closeTableRow()
    {
        return $this->rowData = '</tr>';
    }
    
    public function getBody()
    {
        return '<tbody>';
    }
    
    public function closeBody()
    {
        return '</tbody>';
    }
    
    public function setRowData($text = '', $attributes = array())
    {
        $tmpRowData = '<td ';
        foreach($attributes as $attr => $val) {
            $tmpRowData .= $attr .'="'.$val.'" ';
        }
        $tmpRowData .= '>' . $text . '</td>';
        $this->rowData = $tmpRowData;
       
        return $this->rowData;
    }
    
    
    
    public function getTable()
    {
        return $this->table;
    }
    
    public function getColumns()
    {
        return '<thead><tr>' . $this->columns . '</tr></thead>';
    }
    
    public function getData()
    {
        return  $this->rowData;
    }
    
    
    public function render()
    {
        $toolTipTable = $this->getTable() . $this->getColumns() . $this->getData();
        
        return $toolTipTable . '</table>';
    }
}