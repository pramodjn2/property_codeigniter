<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Blog_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 	
	function blogCategoryList($category_id=''){
		if($category_id !=''){
			$this->db->where('blog_cat_id', $category_id);
		}
		$this->db->where('status','Active');
		$result = $this->db->get('blog_category')->result_array();
		return $result;
	}
	
	function blogCategoryPostTotal($category_id){
		if($category_id !=''){
			$this->db->where('category_id', $category_id);
			$this->db->where('status', 'Active');
			$this->db->from('blog');			
			return $this->db->count_all_results();			 
		}else{
			return '0';
		}
		
	}
	
	function blogCategoryPage($category_id, $per_page_limit = 10, $per_page_offset=1){
		$this->db->select("b.*, CONCAT(u.firstName,' ',lastname) as postedBy, bc.categoryName, bc.category_image, (SELECT COUNT(blogc.blog_comment_id) from blog_comment blogc where blogc.blog_id = b.blog_id GROUP BY blogc.blog_id) as totalComment", false);
		$this->db->from('blog b');
		$this->db->join('blog_category bc', 'bc.blog_cat_id = b.category_id', 'LEFT');
		$this->db->join('user u', 'u.user_id = b.user_id', 'LEFT');
		$this->db->where('b.category_id',$category_id);		
		$this->db->where('b.status','Active');
		
		$this->db->order_by('b.createdDate','DESC');
		$this->db->limit($per_page_limit, $per_page_offset);
		
		$result = $this->db->get();
		$totalRecords =	$result->num_rows();
		$resultData =	$result->result_array();
		$prepareData = array('totalRecords' => $totalRecords, 'resultData'=>$resultData);
		return $prepareData;
	}
    function blog($blog_id=NULL){

     $this->db->select('b.*, u.firstName,u.lastName,u.about_us, u.profile_image,  (SELECT COUNT(bc.blog_comment) from blog_comment bc where bc.blog_id = b.blog_id  GROUP BY bc.blog_id) as blog_comment_count',false);

        $this->db->from('blog b');

  $this->db->join('user u', 'u.user_id = b.user_id', 'LEFT');

  $this->db->where('b.status', 'Active');

  if(!empty($blog_id)){

    $this->db->where('b.blog_id', $blog_id); 

  }

  $query = $this->db->get();

  if($query->num_rows()>0){

            return $query->result_array();

        }else{

            return false;

        }

    }
	function getBlogComments($blog_id = NULL){

    if(empty($blog_id)){

       return false;

       }

  $this->db->select('blgc.blog_id,blgc.blog_comment,blgc.createdDate,blgc.blog_comment_id,u.user_id,u.firstName,u.lastName,u.profile_image');

  $this->db->from('blog_comment blgc');

  $this->db->join('user u', 'blgc.user_id = u.user_id', 'LEFT');

  $this->db->where('blgc.blog_id', $blog_id);
  
  $this->db->where('blgc.blog_parent_id', '0');

  $query = $this->db->get();

       if($query->num_rows()>0){

  $result =  $query->result_array();

       return  $result;

    }else{ 

      return false;

    }

   }
	
	function blogSearchResultTotal($keyWords){
		$totalRecords = 0;
		if($keyWords !=''){
			$this->db->select("b.*, CONCAT(u.firstName,' ',lastname) as postedBy, bc.categoryName, bc.category_image, (SELECT COUNT(blogc.blog_comment_id) from blog_comment blogc where blogc.blog_id = b.blog_id GROUP BY blogc.blog_id) as totalComment", false);
			$this->db->from('blog b');
			$this->db->join('blog_category bc', 'bc.blog_cat_id = b.category_id', 'LEFT');
			$this->db->join('user u', 'u.user_id = b.user_id', 'LEFT');
			$this->db->like('b.blog_title', $keyWords);
			$this->db->or_like('b.blog_description', $keyWords);
			$this->db->or_like('b.blog_tags', $keyWords);			
			$this->db->where('b.status','Active');
			
			$result = $this->db->get();
			$totalRecords =	$result->num_rows();			
			return $totalRecords;
		}else{
			return $totalRecords;
		}
	}
   
    function blogSearchResult($keyWords, $per_page_limit = 10, $per_page_offset=1){
		if($keyWords !=''){			
			$this->db->select("b.*, CONCAT(u.firstName,' ',lastname) as postedBy, bc.categoryName, bc.category_image, (SELECT COUNT(blogc.blog_comment_id) from blog_comment blogc where blogc.blog_id = b.blog_id GROUP BY blogc.blog_id) as totalComment", false);
			$this->db->from('blog b');
			$this->db->join('blog_category bc', 'bc.blog_cat_id = b.category_id', 'LEFT');
			$this->db->join('user u', 'u.user_id = b.user_id', 'LEFT');
			$this->db->like('b.blog_title', $keyWords);
			$this->db->or_like('b.blog_description', $keyWords);
			$this->db->or_like('b.blog_tags', $keyWords);			
			$this->db->where('b.status','Active');
			$this->db->order_by('b.createdDate','DESC');
			$this->db->limit($per_page_limit, $per_page_offset);
			
			$result = $this->db->get();
			$totalRecords =	$result->num_rows();
			$resultData =	$result->result_array();				
			return $resultData;
		}else{
			return array();
		}
	}
	
	function blogPostCommentTotal($blog_id){
		if($blog_id !=''){
			$this->db->where('blog_id', $blog_id);
			$this->db->where('status', 'Active');
			$this->db->from('blog_comment');			
			return $this->db->count_all_results();			 
		}else{
			return '0';
		}
	}
	function blogPostCommentData($blog_id, $per_page_limit = 10, $per_page_offset=1){
		
		if($blog_id !=''){
			/*$this->db->select('bc.blog_comment_id, bc.blog_comment, bc.createdDate');
			$this->db->from('blog_comment bc');
			$this->db->join('blog_comment bc2', 'bc2.blog_parent_id = bc.blog_comment_id', 'LEFT');
			$this->db->where('bc2.blog_parent_id', '0');
			$this->db->where('bc.status', 'Active');
			$this->db->where('bc.blog_id', $blog_id);			
			$this->db->order_by('bc.createdDate','DESC');
			$this->db->limit($per_page_limit, $per_page_offset);			
			$result = $this->db->get();
			$resultData = $result->result_array();
			
			SELECT * ,
(CASE
    WHEN blog_parent_id = blog_comment_id OR blog_parent_id = 0  THEN blog_comment_id
    WHEN blog_parent_id <> blog_comment_id THEN blog_parent_id
END) AS 'the_parent'
FROM blog_comment ORDER BY the_parent ASC, blog_parent_id ASC 
			
			*/
			
			$query = $this->db->query("select c.*, CONCAT(u.firstName,' ', lastName) as commentedBy,u.user_id as bloggerid, u.profile_image from (
					  select c.*,
					  coalesce(nullif(c.blog_parent_id, 0), c.blog_comment_id) as groupID,
					  case when c.blog_parent_id = 0 then 1 else 0 end as isparent,
					  case when p.blog_parent_id = 0 then c.blog_comment_id end as orderbyint
					  from blog_comment c
					  left join blog_comment p on p.blog_comment_id = c.blog_parent_id
					) c left join user u on u.user_id = c.user_id WHERE c.blog_id = '".$blog_id."' order by groupID, isparent desc, orderbyint,createdDate desc ");
			
			$resultData = $query->result_array();
			
			return $resultData;
		}else{
			return array();
		}
	}
	
	
	function popularBlog(){
		$this->db->select("b.blog_id, b.user_id, b.blog_title, b.createdDate, CONCAT(u.firstName,' ', lastName) as postedBy, u.profile_image, (SELECT COUNT(blogc.blog_comment_id) from blog_comment blogc where blogc.blog_id = b.blog_id GROUP BY blogc.blog_id) as totalComment", false);
		$this->db->from('blog b');
		$this->db->join('user u', 'u.user_id = b.user_id');
		$this->db->where('b.status','Active');
		$this->db->group_by('b.blog_id');
		$this->db->order_by('totalComment', 'DESC');
		$this->db->limit(5,0);
		$result = $this->db->get();
		$totalRecords =	$result->num_rows();
		$resultData =	$result->result_array();
		//$prepareData = array('totalRecords' => $totalRecords, 'resultData'=>$resultData);
		return $resultData;
	}
    function recentPostedBlog(){
		$this->db->select("b.blog_id, b.user_id, b.blog_title, b.createdDate, CONCAT(u.firstName,' ', lastName) as postedBy, u.profile_image", false);
		$this->db->from('blog b');
		$this->db->join('user u', 'u.user_id = b.user_id');
		$this->db->where('b.status','Active');
		$this->db->group_by('b.blog_id');
		$this->db->order_by('b.createdDate', 'DESC');
		$this->db->limit(5,0);
		$result = $this->db->get();
		$totalRecords =	$result->num_rows();
		$resultData =	$result->result_array();
		//$prepareData = array('totalRecords' => $totalRecords, 'resultData'=>$resultData);
		return $resultData;
	}
     
}
 
/* End of file blog_model.php */
/* Location: ./application/models/blog_model.php */
