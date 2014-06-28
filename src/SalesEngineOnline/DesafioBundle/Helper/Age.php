<?php

namespace SalesEngineOnline\DesafioBundle\Helper;

/**
 * Description of Age
 *
 * @author Pedro Resende <pedroresende@mail.resende.biz>
 */
class Age {

    private $year;
    private $month;
    private $day;

    public function __construct($year, $month, $day) {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    public function validate() {
        $birthday = \DateTime::createFromFormat('d-m-Y', $this->day.'-'.$this->month.'-'.$this->year);
        $today = new \DateTime();
        $diff = $today->diff($birthday);
        if($diff->format('%Y') < 21) {
            return false;
        } else {
            return true;
        }

    }
}
