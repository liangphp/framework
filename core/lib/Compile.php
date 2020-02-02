<?php
namespace core\lib;

class Compile
{
	// 待编译文件
	private $template;
	// 需要替换的文件
	private $content;
	// 编译后的文件
	private $compile;
	// 左定界符
	private $left;
	// 右定界符
	private $right;
	// 值栈
	public $assign = [];
	// 匹配正则数组
	private $pattern = [];
	// 替换数组
	private $replacement = [];
    private $T_P = array();   // 匹配正则数组
    private $T_R = array();

	public function __construct($template, $compileFile, $config)
	{
		// 模板文件
		$this->template = $template;

		// 编译文件
        $this->comfile = $compileFile;

        $this->config = $config;

        // 模板文件内容
        $this->content = file_get_contents($template);

        $this->pattern();


	}

	public function pattern()
	{
		// 是否支持原生PHP代码
        if ($this->config['php_turn'] === true) {
            // <?php
            $this->T_P[] = "#<\?(=|php|)(.+?)\?#is";
            $this->T_R[] = "&lt;?\1\2?&gt;";
        }

        // 变量匹配
        // \x7f-\xff表示ASCII字符从127到255，其中\为转义，作用是匹配汉字
        // 匹配 {$name}  
        $this->T_P[] = "#\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}#";
        // 变量替换
        $this->T_R[] = "<?php echo \$\\1;?>";


        // foreach标签盘匹配
        // 起始标签 {$foreach $a}  {$loop $a}
        // $this->T_P[] = "#\{(loop|foreach)\s+\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\}#i";
        $this->T_P[] = "#\{(loop|foreach)\s+\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s+\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s+\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\}#i";
        // 结束标签 {/foreach}
        $this->T_P[] = "#\{\/(loop|foreach|if)\}#";
        // $this->T_P[] = "#\{\$([k|v])\}#";
        // foreach标签替换
        $this->T_R[] = "<?php foreach ((array)\$\\2 as \$\\3 => \$\\4) { ?>";
        $this->T_R[] = "<?php } ?>";
        /*$this->T_R[] = "<?php echo \$\\1?>";*/



        // if else标签匹配
        $this->T_P[] = "#\{if (.*?)\}#";
        $this->T_P[] = "#\{(else if|elseif) (.*?)\}#";
        $this->T_P[] = "#\{else\}#";
        $this->T_P[] = "#\{(\#|\*)(.*?)(\#|\*)\}#";
        // if else标签替换
        $this->T_R[] = "<?php if(\\1){ ?>";
        $this->T_R[] = "<?php }elseif(\\2){ ?>";
        $this->T_R[] = "<?php }else{ ?>";
        $this->T_R[] = "";

        // 注释
        $this->T_P[] = '#\{//(.*?)\}#s';
        $this->T_P[] = '#\{/\*(.*?)\*/}#s';
        // 替换
        $this->T_R[] = '<?php /*\1*/ ?>';
        $this->T_R[] = '<?php /*\1*/ ?>';

        // 模板输出替换
        foreach ($this->config['tpl_replace_string'] as $key => $value) {
            $this->T_P[] = '/' . $key . '/';
            $this->T_R[] = $value;
        }
	}

	public function compile() {
        // $this->c_var();
        //$this->c_staticFile();
        
        // preg_match_all("#\{(loop|foreach)\s+\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s+\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s+\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\}#i", $this->content, $matches);
        // var_dump($matches);


        // echo '<pre>';
        // var_dump($this->T_P,$this->T_R);
        // echo '</pre>';

        $this->content = preg_replace($this->T_P, $this->T_R, $this->content);

        // 将内容写入编译文件中
        file_put_contents($this->comfile, $this->content);
    }
}

