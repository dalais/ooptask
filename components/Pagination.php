<?php
namespace MyClasses\components;
/*
 * Pagination
 */
class Pagination
{
    /**
     *
     * @var int The navigation link to the page
     *
     */
    private $max = 10;

    /**
     *
     * @var string Key for GET, in which is written the page number
     *
     */
    private $index = 'page';

    /**
     *
     * @var int Current page
     *
     */
    private $current_page;

    /**
     *
     * @var int The total numbers of records
     *
     */
    private $total;

    /**
     *
     * @var int Limit records per page
     *
     */
    private $limit;

    /**
     * Run the required data for navigation
     *
     * @param int $total Total numbers of records
     * @param int $currentPage Number current page
     * @param int $limit Number records of the page
     * @param int $index Key for url
     */
    public function __construct($total, $currentPage, $limit, $index)
    {

        $this->total = $total;

        $this->limit = $limit;

        $this->index = $index;

        # Set the number of pages
        $this->amount = $this->amount();

        $this->setCurrentPage($currentPage);
    }

    /**
     * For output links
     *
     * @return string The HTML-code with navigation links
     */
    public function get()
    {
        # For the record links
        $links = null;

        # The resulting limit cycle
        $limits = $this->limits();

        $html = '<ul class="pagination">';

        # Generation links
        for ($page = $limits[0]; $page <= $limits[1]; $page++) {

            # If it is the current page, no link and adds a class active
            if ($page == $this->current_page) {
                $links .= '<li class="active"><a href="#">' . $page . '</a></li>';
            } else {
                # Else generation link
                $links .= $this->generateHtml($page);
            }
        }

        # If the links are created
        if (!is_null($links)) {

            # If current page is not first
            if ($this->current_page > 1)

                # Is created link "At first"
                $links = $this->generateHtml(1, '&lt;') . $links;

            # If current page is not first
            if ($this->current_page < $this->amount)
                # Is created link "At last"
                $links .= $this->generateHtml($this->amount, '&gt;');
        }

        $html .= $links . '</ul>';

        # Return html
        return $html;
    }

    /**
     * To generate the HTML code for the link
     *
     * @param integer $page - number page
     *
     * @return string The generated HTML link code
     */
    private function generateHtml($page, $text = null)
    {
        # If text link is not specified
        if (!$text)

            # Specify that the text - figure page
            $text = $page;
        $currentURI = rtrim($_SERVER['REQUEST_URI'], '/') . '/';
        $currentURI = preg_replace('~/page-[0-9]+~', '', $currentURI);

        # The generated HTML code references and return
        return
            '<li><a href="' . $currentURI . $this->index . $page . '">' . $text . '</a></li>';
    }

    /**
     *  To release where to start
     *
     * @return array An array with the beginning and the end of the countdown
     */
    private function limits()
    {
        # Computed links to the left (to active the link was in the middle)
        $left = $this->current_page - round($this->max / 2);

        # Computed the beginning countdown
        $start = $left > 0 ? $left : 1;

        # If there is a minimum of $this->max pages
        if ($start + $this->max <= $this->amount) {

            # Assign the forward end of the loop $this->max pages, or just the minimum
            $end = $start > 1 ? $start + $this->max : $this->max;
        } else {

            # End - the total number of pages
            $end = $this->amount;

            # Beginning - minus $this->max end
            $start = $this->amount - $this->max > 0 ? $this->amount - $this->max : 1;
        }

        return
            array($start, $end);
    }
    /**
     * Set the current page
     *
     * @return
     */
    private function setCurrentPage($currentPage)
    {
        # Get number page
        $this->current_page = $currentPage;

        # If current page is greater than zero
        if ($this->current_page > 0) {

            # If current page is less total number page
            if ($this->current_page > $this->amount)

                # Set page to the last
                $this->current_page = $this->amount;
        } else
            # Set on the first page
            $this->current_page = 1;
    }


    /**
     * Get total pages
     *
     * @return int Number pages
     */
    private function amount()
    {
        # Divide and return
        return ceil($this->total / $this->limit);
    }
}