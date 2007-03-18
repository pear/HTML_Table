<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * PEAR::HTML_Table makes the design of HTML tables easy, flexible, reusable and efficient.
 *
 * The PEAR::HTML_Table package provides methods for easy and efficient design of HTML tables.
 * - Lots of customization options.
 * - Tables can be modified at any time.
 * - The logic is the same as standard HTML editors.
 * - Handles col and rowspans.
 * - PHP code is shorter, easier to read and to maintain.
 * - Tables options can be reused.
 *
 * For auto filling of data and such then check out http://pear.php.net/package/HTML_Table_Matrix
 *
 * PHP versions 4 and 5
 *
 * LICENSE:
 * 
 * Copyright (c) 2005-2007, Adam Daniel <adaniel1@eesus.jnj.com>,
 *                          Bertrand Mansion <bmansion@mamasam.com>,
 *                          Mark Wiesemann <wiesemann@php.net>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *    * Redistributions of source code must retain the above copyright
 *      notice, this list of conditions and the following disclaimer.
 *    * Redistributions in binary form must reproduce the above copyright
 *      notice, this list of conditions and the following disclaimer in the 
 *      documentation and/or other materials provided with the distribution.
 *    * The names of the authors may not be used to endorse or promote products 
 *      derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS
 * IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY
 * OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

 *
 * @category   HTML
 * @package    HTML_Table
 * @author     Adam Daniel <adaniel1@eesus.jnj.com>
 * @author     Bertrand Mansion <bmansion@mamasam.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/HTML_Table
 */


/**
* Requires PEAR, HTML_Common and HTML_Table_Storage
*/
require_once 'PEAR.php';
require_once 'HTML/Common.php';
require_once 'HTML/Table/Storage.php';

/**
 * PEAR::HTML_Table makes the design of HTML tables easy, flexible, reusable and efficient.
 *
 * The PEAR::HTML_Table package provides methods for easy and efficient design of HTML tables.
 * - Lots of customization options.
 * - Tables can be modified at any time.
 * - The logic is the same as standard HTML editors.
 * - Handles col and rowspans.
 * - PHP code is shorter, easier to read and to maintain.
 * - Tables options can be reused.
 *
 * For auto filling of data and such then check out http://pear.php.net/package/HTML_Table_Matrix
 *
 * @category   HTML
 * @package    HTML_Table
 * @author     Adam Daniel <adaniel1@eesus.jnj.com>
 * @author     Bertrand Mansion <bmansion@mamasam.com>
 * @copyright  2005-2006 The PHP Group
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/HTML_Table
 */
class HTML_Table extends HTML_Common {

    /**
     * Value to insert into empty cells. This is used as a default for newly-created tbodies.
     * @var    string
     * @access private
     */
    var $_autoFill = '&nbsp;';

    /**
     * Automatically adds a new row or column if a given row or column index does not exist.
     * This is used as a default for newly-created tbodies.
     * @var    bool
     * @access private
     */
    var $_autoGrow = true;

    /**
     * Array containing the table caption
     * @var     array
     * @access  private
     */
    var $_caption = array();

    /**
     * Array containing the table column group specifications
     *
     * @var     array
     * @author  Laurent Laville (pear at laurent-laville dot org)
     * @access  private
     */
    var $_colgroup = array();

    /**
     * HTML_Table_Storage object for the (t)head of the table
     * @var    object
     * @access private
     */
    var $_thead = null;

    /**
     * HTML_Table_Storage object for the (t)foot of the table
     * @var    object
     * @access private
     */
    var $_tfoot = null;

    /**
     * HTML_Table_Storage object for the (t)body of the table
     * @var    object
     * @access private
     */
    var $_tbodies = array();

    /**
     * Whether to use <thead>, <tfoot> and <tbody> or not
     * @var    bool
     * @access private
     */
    var $_useTGroups = false;

    /**
     * Class constructor
     * @param    array    $attributes        Associative array of table tag attributes
     * @param    int      $tabOffset         Tab offset of the table
     * @param    bool     $useTGroups        Whether to use <thead>, <tfoot> and
     *                                       <tbody> or not
     * @param    mixed    $tbody             (optional) The index of the initial body.
     * @access   public
     */
    function HTML_Table($attributes = null, $tabOffset = 0, $useTGroups = false, $tbody = 0)
    {
        HTML_Common::HTML_Common($attributes, (int)$tabOffset);
        $this->_useTGroups = (boolean)$useTGroups;
        $this->_tbodies[$tbody] =& new HTML_Table_Storage($tabOffset, $this->_useTGroups);
        if ($this->_useTGroups) {
            $this->_thead =& new HTML_Table_Storage($tabOffset, $this->_useTGroups);
            $this->_tfoot =& new HTML_Table_Storage($tabOffset, $this->_useTGroups);
        }
    }

    /**
     * Returns the API version
     * @access  public
     * @return  double
     * @deprecated
     */
    function apiVersion()
    {
        return 1.7;
    }

    /**
     * Returns the HTML_Table_Storage object for <thead>
     * @access  public
     * @return  object
     */
    function &getHeader()
    {
        if (is_null($this->_thead)) {
            $this->_useTGroups = true;
            $this->_thead =& new HTML_Table_Storage($this->_tabOffset,
                                                    $this->_useTGroups);
            foreach (array_keys($this->_tbodies) as $tbody) {
                $this->_tbodies[$tbody]->setUseTGroups(true);
            }
        }
        return $this->_thead;
    }

    /**
     * Returns the HTML_Table_Storage object for <tfoot>
     * @access  public
     * @return  object
     */
    function &getFooter()
    {
        if (is_null($this->_tfoot)) {
            $this->_useTGroups = true;
            $this->_tfoot =& new HTML_Table_Storage($this->_tabOffset,
                                                    $this->_useTGroups);
            foreach (array_keys($this->_tbodies) as $tbody) {
                $this->_tbodies[$tbody]->setUseTGroups(true);
            }
        }
        return $this->_tfoot;
    }

    /**
     * Returns the HTML_Table_Storage object for <tbody>
     * (or the whole table if <t{head|foot|body}> is not used)
     * @param   mixed     $tbody             (optional) The index of the body to return.
     * @access  public
     * @return  object
     */
    function &getBody($tbody = 0)
    {
        if (!isset($this->_tbodies[$tbody])) {
            if (!$this->_useTGroups) {
                foreach (array_keys($this->_tbodies) as $tbodyEach) {
                    $this->_tbodies[$tbodyEach]->setUseTGroups(true);
                }
            }
            $this->_useTGroups = true;
            $this->_tbodies[$tbody] =& new HTML_Table_Storage($this->_tabOffset,
                                                              $this->_useTGroups);
            $this->_tbodies[$tbody]->setAutoFill($this->_autoFill);
        }
        return $this->_tbodies[$tbody];
    }

    /**
     * Sets the table caption
     * @param   string    $caption
     * @param   mixed     $attributes        Associative array or string of table row attributes
     * @access  public
     */
    function setCaption($caption, $attributes = null)
    {
        $attributes = $this->_parseAttributes($attributes);
        $this->_caption = array('attr' => $attributes, 'contents' => $caption);
    }

    /**
     * Sets the table columns group specifications, or removes existing ones.
     *
     * @param   mixed     $colgroup         (optional) Columns attributes
     * @param   mixed     $attributes       (optional) Associative array or string
     *                                                 of table row attributes
     * @author  Laurent Laville (pear at laurent-laville dot org)
     * @access  public
     */
    function setColGroup($colgroup = null, $attributes = null)
    {
        if (isset($colgroup)) {
            $attributes = $this->_parseAttributes($attributes);
            $this->_colgroup[] = array('attr' => $attributes,
                                       'contents' => $colgroup);
        } else {
            $this->_colgroup = array();
        }
    }

    /**
     * Sets the autoFill value
     * @param   mixed   $fill
     * @param   mixed   $tbody             (optional) The index of the body to set.
     *                                     Pass null to set for all bodies.
     * @access  public
     */
    function setAutoFill($fill, $tbody = null)
    {
        if (!is_null($tbody)) {
            $this->_tbodies[$tbody]->setAutoFill($fill);
        } else {
            $this->_autoFill = $fill;
            foreach (array_keys($this->_tbodies) as $tbody) {
                $this->_tbodies[$tbody]->setAutoFill($fill);
            }
        }
    }

    /**
     * Returns the autoFill value
     * @param    mixed       $tbody  (optional) The index of the body to get.
     *                               Pass null to get the default for new bodies.
     * @access   public
     * @return   mixed
     */
    function getAutoFill($tbody = null)
    {
        if (!is_null($tbody)) {
            return $this->_tbodies[$tbody]->getAutoFill();
        } else {
            return $this->_autoFill;
        }
    }

    /**
     * Sets the autoGrow value
     * @param    bool   $grow
     * @param    mixed  $tbody             (optional) The index of the body to set.
     *                                     Pass null to set for all bodies.
     * @access   public
     */
    function setAutoGrow($grow, $tbody = null)
    {
        if (!is_null($tbody)) {
            $this->_tbodies[$tbody]->setAutoGrow($grow);
        } else {
            $this->_autoGrow = $grow;
            foreach (array_keys($this->_tbodies) as $tbody) {
                $this->_tbodies[$tbody]->setAutoGrow($grow);
            }
        }
    }

    /**
     * Returns the autoGrow value
     * @param    mixed       $tbody          (optional) The index of the body to get.
     *                                       Pass null to get the default for new bodies.
     * @access   public
     * @return   mixed
     */
    function getAutoGrow($tbody = null)
    {
        if (!is_null($tbody)) {
            return $this->_tbodies[$tbody]->getAutoGrow();
        } else {
            return $this->_autoGrow;
        }
    }

    /**
     * Sets the number of rows in the table body
     * @param    int     $rows
     * @param    mixed   $tbody              (optional) The index of the body to set.
     * @access   public
     */
    function setRowCount($rows, $tbody = 0)
    {
        $this->_tbodies[$tbody]->setRowCount($rows);
    }

    /**
     * Sets the number of columns in the table
     * @param    int     $cols
     * @param    mixed   $tbody       (optional) The index of the body to set.
     * @access   public
     */
    function setColCount($cols, $tbody = 0)
    {
        $this->_tbodies[$tbody]->setColCount($cols);
    }

    /**
     * Returns the number of rows in the table
     * @param    mixed       $tbody          (optional) The index of the body to get.
     *                                       Pass null to get the total number of rows in all bodies.
     * @access   public
     * @return   int
     */
    function getRowCount($tbody = null)
    {
        if (!is_null($tbody)) {
            return $this->_tbodies[$tbody]->getRowCount();
        } else {
            $rowCount = 0;
            foreach (array_keys($this->_tbodies) as $tbody) {
                $rowCount += $this->_tbodies[$tbody];
            }
            return $rowCount;
        }
    }

    /**
     * Gets the number of columns in the table
     *
     * If a row index is specified, the count will not take
     * the spanned cells into account in the return value.
     *
     * @param    int    $row         Row index to serve for cols count
     * @param    mixed  $tbody       (optional) The index of the body to get.
     * @access   public
     * @return   int
     */
    function getColCount($row = null, $tbody = 0)
    {
        return $this->_tbodies[$tbody]->getColCount($row);
    }

    /**
     * Sets a rows type 'TH' or 'TD'
     * @param    int         $row    Row index
     * @param    string      $type   'TH' or 'TD'
     * @param    mixed       $tbody  (optional) The index of the body to set.
     * @access   public
     */

    function setRowType($row, $type, $tbody = 0)
    {
        $this->_tbodies[$tbody]->setRowType($row, $type);
    }

    /**
     * Sets a columns type 'TH' or 'TD'
     * @param    int         $col    Column index
     * @param    string      $type   'TH' or 'TD'
     * @param    mixed       $tbody  (optional) The index of the body to set.
     *                               Pass null to set for all bodies.
     * @access   public
     */
    function setColType($col, $type, $tbody = null)
    {
        if (!is_null($tbody)) {
            $this->_tbodies[$tbody]->setColType($col, $type);
        } else {
            foreach (array_keys($this->_tbodies) as $tbody) {
                $this->_tbodies[$tbody]->setColType($col, $type);
            }
        }
    }

    /**
     * Sets the cell attributes for an existing cell.
     *
     * If the given indices do not exist and autoGrow is true then the given
     * row and/or col is automatically added.  If autoGrow is false then an
     * error is returned.
     * @param    int        $row         Row index
     * @param    int        $col         Column index
     * @param    mixed      $attributes  Associative array or string of table row attributes
     * @param    mixed      $tbody       (optional) The index of the body to set.
     * @access   public
     * @throws   PEAR_Error
     */
    function setCellAttributes($row, $col, $attributes, $tbody = 0)
    {
        $ret = $this->_tbodies[$tbody]->setCellAttributes($row, $col, $attributes);
        if (PEAR::isError($ret)) {
            return $ret;
        }
    }

    /**
     * Updates the cell attributes passed but leaves other existing attributes intact
     * @param    int     $row         Row index
     * @param    int     $col         Column index
     * @param    mixed   $attributes  Associative array or string of table row attributes
     * @param    mixed   $tbody       (optional) The index of the body to set.
     * @access   public
     */
    function updateCellAttributes($row, $col, $attributes, $tbody = 0)
    {
        $ret = $this->_tbodies[$tbody]->updateCellAttributes($row, $col, $attributes);
        if (PEAR::isError($ret)) {
            return $ret;
        }
    }

    /**
     * Returns the attributes for a given cell
     * @param    int     $row         Row index
     * @param    int     $col         Column index
     * @param    mixed   $tbody       (optional) The index of the body to get.
     * @return   array
     * @access   public
     */
    function getCellAttributes($row, $col, $tbody = 0)
    {
        return $this->_tbodies[$tbody]->getCellAttributes($row, $col);
    }

    /**
     * Sets the cell contents for an existing cell
     *
     * If the given indices do not exist and autoGrow is true then the given
     * row and/or col is automatically added.  If autoGrow is false then an
     * error is returned.
     * @param    int      $row        Row index
     * @param    int      $col        Column index
     * @param    mixed    $contents   May contain html or any object with a toHTML method;
     *                                if it is an array (with strings and/or objects), $col
     *                                will be used as start offset and the array elements
     *                                will be set to this and the following columns in $row
     * @param    string   $type       (optional) Cell type either 'TH' or 'TD'
     * @param    mixed    $tbody      (optional) The index of the body to set.
     * @access   public
     * @throws   PEAR_Error
     */
    function setCellContents($row, $col, $contents, $type = 'TD', $tbody = 0)
    {
        $ret = $this->_tbodies[$tbody]->setCellContents($row, $col, $contents, $type);
        if (PEAR::isError($ret)) {
            return $ret;
        }
    }

    /**
     * Returns the cell contents for an existing cell
     * @param    int        $row    Row index
     * @param    int        $col    Column index
     * @param    mixed      $tbody  (optional) The index of the body to get.
     * @access   public
     * @return   mixed
     */
    function getCellContents($row, $col, $tbody = 0)
    {
        return $this->_tbodies[$tbody]->getCellContents($row, $col);
    }

    /**
     * Sets the contents of a header cell
     * @param    int     $row
     * @param    int     $col
     * @param    mixed   $contents
     * @param    mixed   $attributes  Associative array or string of table row attributes
     * @param    mixed   $tbody       (optional) The index of the body to set.
     * @access   public
     */
    function setHeaderContents($row, $col, $contents, $attributes = null, $tbody = 0)
    {
        $this->_tbodies[$tbody]->setHeaderContents($row, $col, $contents, $attributes);
    }

    /**
     * Adds a table row and returns the row identifier
     * @param    array    $contents   (optional) Must be a indexed array of valid cell contents
     * @param    mixed    $attributes (optional) Associative array or string of table row attributes
     *                                This can also be an array of attributes, in which case the attributes
     *                                will be repeated in a loop.
     * @param    string   $type       (optional) Cell type either 'th' or 'td'
     * @param    bool     $inTR       false if attributes are to be applied in TD tags
     *                                true if attributes are to be applied in TR tag
     * @param    mixed    $tbody      (optional) The index of the body to use.
     * @return   int
     * @access   public
     */
    function addRow($contents = null, $attributes = null, $type = 'td', $inTR = false, $tbody = 0)
    {
        $ret = $this->_tbodies[$tbody]->addRow($contents, $attributes, $type, $inTR);
        return $ret;
    }

    /**
     * Sets the row attributes for an existing row
     * @param    int      $row            Row index
     * @param    mixed    $attributes     Associative array or string of table row attributes
     *                                    This can also be an array of attributes, in which case the attributes
     *                                    will be repeated in a loop.
     * @param    bool     $inTR           false if attributes are to be applied in TD tags
     *                                    true if attributes are to be applied in TR tag
     * @param    mixed    $tbody          (optional) The index of the body to set.
     * @access   public
     * @throws   PEAR_Error
     */
    function setRowAttributes($row, $attributes, $inTR = false, $tbody = 0)
    {
        $ret = $this->_tbodies[$tbody]->setRowAttributes($row, $attributes, $inTR);
        if (PEAR::isError($ret)) {
            return $ret;
        }
    }

    /**
     * Updates the row attributes for an existing row
     * @param    int      $row            Row index
     * @param    mixed    $attributes     Associative array or string of table row attributes
     * @param    bool     $inTR           false if attributes are to be applied in TD tags
     *                                    true if attributes are to be applied in TR tag
     * @param    mixed    $tbody          (optional) The index of the body to set.
     * @access   public
     * @throws   PEAR_Error
     */
    function updateRowAttributes($row, $attributes = null, $inTR = false, $tbody = 0)
    {
        $ret = $this->_tbodies[$tbody]->updateRowAttributes($row, $attributes, $inTR);
        if (PEAR::isError($ret)) {
            return $ret;
        }
    }

    /**
     * Returns the attributes for a given row as contained in the TR tag
     * @param    int     $row         Row index
     * @param    mixed   $tbody       (optional) The index of the body to get.
     * @return   array
     * @access   public
     */
    function getRowAttributes($row, $tbody = 0)
    {
        return $this->_tbodies[$tbody]->getRowAttributes($row);
    }

    /**
     * Alternates the row attributes starting at $start
     * @param    int      $start            Row index of row in which alternating begins
     * @param    mixed    $attributes1      Associative array or string of table row attributes
     * @param    mixed    $attributes2      Associative array or string of table row attributes
     * @param    bool     $inTR             false if attributes are to be applied in TD tags
     *                                      true if attributes are to be applied in TR tag
     * @param    int      $firstAttributes  (optional) Which attributes should be
     *                                      applied to the first row, 1 or 2.
     * @param    mixed    $tbody            (optional) The index of the body to set.
     *                                      Pass null to set for all bodies.
     * @access   public
     */
    function altRowAttributes($start, $attributes1, $attributes2, $inTR = false,
        $firstAttributes = 1, $tbody = null)
    {
        if (!is_null($tbody)) {
            $this->_tbodies[$tbody]->altRowAttributes($start, $attributes1,
                $attributes2, $inTR, $firstAttributes);
        } else {
            foreach (array_keys($this->_tbodies) as $tbody) {
                $this->_tbodies[$tbody]->altRowAttributes($start, $attributes1,
                    $attributes2, $inTR, $firstAttributes);
                // if the tbody's row count is odd, toggle $firstAttributes to
                // prevent the next tbody's first row from having the same
                // attributes as this tbody's last row.
                if ($this->_tbodies[$tbody]->getRowCount() % 2) {
                    $firstAttributes ^= 3;
                }
            }
        }
    }

    /**
     * Adds a table column and returns the column identifier
     * @param    array    $contents   (optional) Must be a indexed array of valid cell contents
     * @param    mixed    $attributes (optional) Associative array or string of table row attributes
     * @param    string   $type       (optional) Cell type either 'th' or 'td'
     * @param    mixed    $tbody      (optional) The index of the body to use.
     * @return   int
     * @access   public
     */
    function addCol($contents = null, $attributes = null, $type = 'td', $tbody = 0)
    {
        return $this->_tbodies[$tbody]->addCol($contents, $attributes, $type);
    }

    /**
     * Sets the column attributes for an existing column
     * @param    int      $col            Column index
     * @param    mixed    $attributes     (optional) Associative array or string of table row attributes
     * @param   mixed     $tbody          (optional) The index of the body to set.
     *                                    Pass null to set for all bodies.
     * @access   public
     */
    function setColAttributes($col, $attributes = null, $tbody = null)
    {
        if (!is_null($tbody)) {
            $this->_tbodies[$tbody]->setColAttributes($col, $attributes);
        } else {
            foreach (array_keys($this->_tbodies) as $tbody) {
                $this->_tbodies[$tbody]->setColAttribute($col, $attributes);
            }
        }
    }

    /**
     * Updates the column attributes for an existing column
     * @param    int      $col            Column index
     * @param    mixed    $attributes     (optional) Associative array or string of table row attributes
     * @param    mixed    $tbody          (optional) The index of the body to set.
     *                                    Pass null to set for all bodies.
     * @access   public
     */
    function updateColAttributes($col, $attributes = null, $tbody = null)
    {
        if (!is_null($tbody)) {
            $this->_tbodies[$tbody]->updateColAttributes($col, $attributes);
        } else {
            foreach (array_keys($this->_tbodies) as $tbody) {
                $this->_tbodies[$tbody]->updateColAttributes($col, $attributes);
            }
        }
    }

    /**
     * Sets the attributes for all cells
     * @param    mixed    $attributes        (optional) Associative array or string of table row attributes
     * @param    mixed    $tbody             (optional) The index of the body to set.
     *                                       Pass null to set for all bodies.
     * @access   public
     */
    function setAllAttributes($attributes = null, $tbody = null)
    {
        if (!is_null($tbody)) {
            $this->_tbodies[$tbody]->setAllAttributes($attributes);
        } else {
            foreach (array_keys($this->_tbodies) as $tbody) {
                $this->_tbodies[$tbody]->setAllAttributes($attributes);
            }
        }
    }

    /**
     * Updates the attributes for all cells
     * @param    mixed    $attributes        (optional) Associative array or string of table row attributes
     * @param    mixed    $tbody             (optional) The index of the body to set.
     *                                       Pass null to set for all bodies.
     * @access   public
     */
    function updateAllAttributes($attributes = null, $tbody = null)
    {
        if (!is_null($tbody)) {
            $this->_tbodies[$tbody]->updateAllAttributes($attributes);
        } else {
            foreach (array_keys($this->_tbodies) as $tbody) {
                $this->_tbodies[$tbody]->updateAllAttributes($attributes);
            }
        }
    }

    /**
     * Returns the table structure as HTML
     * @access  public
     * @return  string
     */
    function toHtml()
    {
        $strHtml = '';
        $tabs = $this->_getTabs();
        $tab = $this->_getTab();
        $lnEnd = $this->_getLineEnd();
        if ($this->_comment) {
            $strHtml .= $tabs . "<!-- $this->_comment -->" . $lnEnd;
        }
        $strHtml .=
            $tabs . '<table' . $this->_getAttrString($this->_attributes) . '>' . $lnEnd;
        if (!empty($this->_caption)) {
            $attr = $this->_caption['attr'];
            $contents = $this->_caption['contents'];
            $strHtml .= $tabs . $tab . '<caption' . $this->_getAttrString($attr) . '>';
            if (is_array($contents)) {
                $contents = implode(', ', $contents);
            }
            $strHtml .= $contents;
            $strHtml .= '</caption>' . $lnEnd;
        }
        if (!empty($this->_colgroup)) {
            foreach ($this->_colgroup as $g => $col) {
                $attr = $this->_colgroup[$g]['attr'];
                $contents = $this->_colgroup[$g]['contents'];
                $strHtml .= $tabs . $tab . '<colgroup' . $this->_getAttrString($attr) . '>';
                if (!empty($contents)) {
                    $strHtml .= $lnEnd;
                    if (!is_array($contents)) {
                        $contents = array($contents);
                    }
                    foreach ($contents as $a => $colAttr) {
                        $attr = $this->_parseAttributes($colAttr);
                        $strHtml .= $tabs . $tab . $tab . '<col' . $this->_getAttrString($attr) . '>' . $lnEnd;
                    }
                    $strHtml .= $tabs . $tab;
                }
                $strHtml .= '</colgroup>' . $lnEnd;
            }
        }
        if ($this->_useTGroups) {
            $tHeadColCount = 0;
            if ($this->_thead !== null) {
                $tHeadColCount = $this->_thead->getColCount();
            }
            $tFootColCount = 0;
            if ($this->_tfoot !== null) {
                $tFootColCount = $this->_tfoot->getColCount();
            }
            $tBodyColCounts = array();
            foreach ($this->_tbodies as $tbody) {
                $tBodyColCounts[] = $tbody->getColCount();
            }
            $tBodyMaxColCount = 0;
            if (count($tBodyColCounts) > 0) {
                $tBodyMaxColCount = max($tBodyColCounts);
            }
            $maxColCount = max($tHeadColCount, $tFootColCount, $tBodyMaxColCount);
            if ($this->_thead !== null) {
                $this->_thead->setColCount($maxColCount);
                if ($this->_thead->getRowCount() > 0) {
                    $strHtml .= $tabs . $tab . '<thead' .
                                $this->_getAttrString($this->_thead->_attributes) .
                                '>' . $lnEnd;
                    $strHtml .= $this->_thead->toHtml($tabs, $tab);
                    $strHtml .= $tabs . $tab . '</thead>' . $lnEnd;
                }
            }
            if ($this->_tfoot !== null) {
                $this->_tfoot->setColCount($maxColCount);
                if ($this->_tfoot->getRowCount() > 0) {
                    $strHtml .= $tabs . $tab . '<tfoot' .
                                $this->_getAttrString($this->_tfoot->_attributes) .
                                '>' . $lnEnd;
                    $strHtml .= $this->_tfoot->toHtml($tabs, $tab);
                    $strHtml .= $tabs . $tab . '</tfoot>' . $lnEnd;
                }
            }
            foreach ($this->_tbodies as $tbody) {
                $tbody->setColCount($maxColCount);
                if ($tbody->getRowCount() > 0) {
                    $strHtml .= $tabs . $tab . '<tbody' .
                                $this->_getAttrString($tbody->_attributes) .
                                '>' . $lnEnd;
                    $strHtml .= $tbody->toHtml($tabs, $tab);
                    $strHtml .= $tabs . $tab . '</tbody>' . $lnEnd;
                }
            }
        } else {
            foreach ($this->_tbodies as $tbody) {
                $strHtml .= $tbody->toHtml($tabs, $tab);
            }
        }
        $strHtml .= $tabs . '</table>' . $lnEnd;
        return $strHtml;
    }

}
?>