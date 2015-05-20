<?php

class mCusPaginationComponent extends Component {

    function paginate ($baseUrl, $queryStr, $totalPages, $page, $limit)
    {
        $stages = 3;
        if($page){
            $start = ($page - 1) * $limit;
        }else{
            $start = 0;
        }
        if ($page == 0){
            $page = 1;
        }
        $prev = $page - 1;
        $next = $page + 1;
        $lastpage = ceil($totalPages/$limit);
        $LastPagem1 = $lastpage - 1;


        $paginate = '';
        if($lastpage > 1)
        {
            $paginate .= "<div class='pagination'>";
            // Previous
            if ($page > 1){
                $paginate.= "<a href='$baseUrl/$queryStr/$prev'>&lt;&lt;</a>";
            }else{
                $paginate.= "";
            }



            // Pages
            if ($lastpage < 7 + ($stages * 2))    // Not enough pages to breaking it up
            {
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page){
                        $paginate.= "<a class='number current' href='#'>$counter</a>";
                    }else{
                        $paginate.= "<a class='number' href='$baseUrl/$queryStr/$counter'>$counter</a>";
                    }
                }
            }
            elseif($lastpage > 5 + ($stages * 2))    // Enough pages to hide a few?
            {
                // Beginning only hide later pages
                if($page < 1 + ($stages * 2))
                {
                    for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
                    {
                        if ($counter == $page){
                            $paginate.= "<a class='number current' href='#'>$counter</a>";
                        }else{
                            $paginate.= "<a class='number' href='$baseUrl/$queryStr/$counter'>$counter</a>";
                        }
                    }
                    $paginate.= "...";
                    $paginate.= "<a class='number' href='$baseUrl/$queryStr/$LastPagem1'>$LastPagem1</a>";
                    $paginate.= "<a class='number' href='$baseUrl/$queryStr/$lastpage'>$lastpage</a>";
                }
                // Middle hide some front and some back
                elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
                {
                    $paginate.= "<a class='number' href='$baseUrl/$queryStr/1'>1</a>";
                    $paginate.= "<a class='number' href='$baseUrl/$queryStr/2'>2</a>";
                    $paginate.= "...";
                    for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
                    {
                        if ($counter == $page){
                            $paginate.= "<a class='number current' href='#'>$counter</a>";
                        }else{
                            $paginate.= "<a class='number' href='$baseUrl/$queryStr/$counter'>$counter</a>";
                        }
                    }
                    $paginate.= "...";
                    $paginate.= "<a class='number' href='$baseUrl/$queryStr/$LastPagem1'>$LastPagem1</a>";
                    $paginate.= "<a class='number' href='$baseUrl/$queryStr/$lastpage'>$lastpage</a>";
                }
                // End only hide early pages
                else
                {
                    $paginate.= "<a class='number' href='$baseUrl/$queryStr/1'>1</a>";
                    $paginate.= "<a class='number' href='$baseUrl/$queryStr/2'>2</a>";
                    $paginate.= "...";
                    for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page){
                            $paginate.= "<a class='number current' href='#'>$counter</a>";
                        }else{
                            $paginate.= "<a class='number' href='$baseUrl/$queryStr/$counter'>$counter</a>";
                        }
                    }
                }
            }

            // Next
            if ($page < $counter - 1){
                $paginate.= "<a class='number' href='$baseUrl/$queryStr/$next'>&gt;&gt;</a>";
            }else{
                $paginate.= "";
            }

            $paginate.= "</div>";
        }

        return $paginate;
    }

}
