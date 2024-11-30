<?php
require_once($CFG->dirroot.'/course/lib.php');
class courseModel
{
    private $course;
    private $id;
    private $courseformatoptions;
    private $max_features = 7;
    private $hasCourse = false;
    private $title = '';
    private $subtitle = '';
    private $price = 0;
    private $duration = '';
    private $image = '/theme/elearnified/images/home/ccnBg.png';
    private $trailer = '/theme/elearnified/images/home/ccnBg.png';
    private $content = [];
    private $isloggedIn = true;
    private $percentage = 0;
    private $promocode = 0;


    public function __construct(object $course)
    {
        global $CFG;

        //check if user is authenticated or guest
        if (!isloggedin() or isguestuser()) {
            $this->setIsloggedIn(false);
        }
        $this->setCourse($course);
        $this->setCourseformatoptions((object) []);
        if( $course && $course->id != 1 ){
            //get course format options
            $format = course_get_format($course);
            $this->setCourseformatoptions((object) $format->get_format_options());
            $this->setHasCourse(true);
            $this->setImage();
        }
    }
    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * set the image by using course image or block image
     */
    public function setImage($image = ''): void
    {
        global $CFG;
        $course = $this->getCourse();
        $image = '';
        if( $this->isHasCourse() ){
            //get course image
            $course->get_course_overviewfiles();
            foreach ($course->get_course_overviewfiles() as $file) {
                $isimage = $file->is_valid_image();
                $url = file_encode_url("{$CFG->wwwroot}/pluginfile.php", '/'. $file->get_contextid(). '/'. $file->get_component(). '/'. $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                $image .=  $url;
            }
        }
        $this->image = $image ?:$CFG->wwwroot.$this->image;
    }

    /**
     * @return string
     */
    public function getTrailer(): string
    {
        return $this->trailer;
    }

    /**
     * set the image by using course format trailer or block trailer
     */
    public function setTrailer($trailer = ''): void
    {
        global $CFG;
        $options =  $this->getCourseformatoptions();
        //get the trailer
        $trailer = '';
        $this->trailer = $trailer ?:$CFG->wwwroot.$this->trailer;
    }
    /*
    |--------------------------------------------------------------------------
    | Getters and Setters
    |--------------------------------------------------------------------------
    |
    | The following lines contains getters and setters for the singleton object
    |
    */
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return string
     */
    public function setTitle(string $title): void
    {
        $course =  $this->getCourse();
        if( $this->isHasCourse() && $course->fullname )
            $title = $course->fullname;
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSubtitle(): string
    {
        return $this->subtitle;
    }

    /**
     * @param string $subtitle'
     * @return string
     */
    public function setSubtitle(string $subtitle): void
    {
        $course =  $this->getCourse();
        if( $this->isHasCourse() && $course->summary )
            $subtitle = $course->summary;
        $this->subtitle = elblkh1helper::format_html_text($subtitle);
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice($price = 0): void
    {

        $options =  $this->getCourseformatoptions();
        if( $this->isHasCourse() && $options->courseprice )
            $price = $options->courseprice;
        $price = $price ? $price : 0;
        $this->price = '₱ '.$price;
    }

    /**
     * @return string
     */
    public function getDuration(): string
    {
        return $this->duration;
    }

    /**
     * @param string $duration
     * @return int
     */
    public function setDuration(string $duration): void
    {
        $options =  $this->getCourseformatoptions();
        if( $this->isHasCourse() && $options->courseduration )
            $duration = $options->courseduration;
        $this->duration = format_time($duration);
    }

    /**
     * @return bool
     */
    public function isHasCourse(): bool
    {
        return $this->hasCourse;
    }

    /**
     * @param bool $hasCourse
     */
    public function setHasCourse(bool $hasCourse): void
    {
        $this->hasCourse = $hasCourse;
    }

    /**
     * @return id
     */
    public function getId(): object
    {
        return $this->id;
    }
    /**
     * @return \core_course_list_element
     */
    public function getCourse(): object
    {
        return $this->course;
    }

    /**
     * @param \core_course_list_element $course
     */
    public function setCourse(object $course): void
    {
        $course = new core_course_list_element($course);
        $this->course = $course;
        $this->id = $course->id;
    }
    /**
     * @return string
     */
    public function getContextid(): string
    {
        return $this->contextid;
    }

    /**
     * @param string $contextid
     * @return string
     */
    public function setContextid(string $contextid): void
    {
        $this->contextid = $contextid;
    }


    /**
     * @return object
     */
    public function getCourseformatoptions(): object
    {
        return $this->courseformatoptions;
    }

    /**
     * @param object $courseformatoptions
     */
    public function setCourseformatoptions($courseformatoptions): void
    {
        $this->courseformatoptions = $courseformatoptions;
    }


    /**
     * @return bool
     */
    public function isIsloggedIn(): bool
    {
        return $this->isloggedIn;
    }

    /**
     * @param bool $isloggedIn
     */
    public function setIsloggedIn(bool $isloggedIn): void
    {
        $this->isloggedIn = $isloggedIn;
    }
    /**
     * @return int
     */
    public function getPercentage(): int
    {
        return $this->percentage;
    }

    /**
     * @param int $percentage
     */
    public function setPercentage(int $percentage): void
    {
        $this->percentage = $percentage;
    }
    /**
     * @return int
     */
    public function getPromocode(): int
    {
        return $this->percentage;
    }

    /**
     * @param int $percentage
     */
    public function setPromocode(int $percentage=0): void
    {
        $this->percentage = $percentage;
    }
}