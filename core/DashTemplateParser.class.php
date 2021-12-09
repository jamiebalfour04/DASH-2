<?php
/*
* This is a project to deploy a more efficient, powerful template parser into Dash. This no longer
* uses the inefficient and less reliable (due to overwrites) str_replace methods but uses a fully
* fledged parser. This parser improves performance over the str_replace methods it succeeds and allows for
* a lot more flexibility with pseudo structures (such as IF). The language used for this templating is called
* DashBoost and it's very simplistic in nature. Firstly, anything enclosed in less than and greater than symbols
* and braces <{ }> is a keyword or variable. Dash can determine a variable from a keyword by searching
* first to check if it is a keyword (precedence of keywords).
*/

class DashTemplateParser extends DashCoreClass
{
    const STRING_TYPE = 0;
    const VAR_TYPE = 1;
    const IF_TYPE = 2;
    const ENDIF_TYPE = 3;
    const NOT_IF_TYPE = 4;

    private $content = "";
    private $program = "";
    private $pc = 0;
    private $inside_keyword = false;
    private $var_list = array();

    public function __construct($string)
    {
        $this->content = $string;
    }

    public function getVariableList(){
      return $this->var_list;
    }

    public function parse()
    {
        //Returns an AST
        return $this->toAST();
    }

    public function traverseAST($current, $list)
    {
        //Transforms an AST to a string
        $output = "";

        while ($current != null) {
            switch ($current->type) {
                case self::STRING_TYPE:
                    //String simply returns the value as it is
                    $output .= $current->string;
                    break;
                case self::VAR_TYPE:
                    //Variables check if the variable list ($list) contains the value get it
                    if (isset($list[$current->string])) {
                        $output .= $list[$current->string];
                    } else {
                        $output .= "";
                    }
                    break;
                case self::IF_TYPE:
                    //IF statement is easy to evaluate, if the value exists and it's not "" then we proceed
                    if (isset($list[$current->string]) && $list[$current->string]) {
                        $output .= $this->traverseAST($current->left, $list);
                    } else {
                        $output .= "";
                    }
                    break;
                case self::NOT_IF_TYPE:
                    //Not if is the reverse of IF
                    if (!isset($list[$current->string]) || $list[$current->string] == "") {
                        $output .= $this->traverseAST($current->left, $list);
                    } else {
                        $output .= "";
                    }
                    break;
                default:
                    $output .= "DashTemplateParser: KEYWORD NOT FOUND";
                    break;
            }
            $current = $current->right;
        }

        return $output;
    }

    private function toAST()
    {
        $root = new DashAST();
        $last = $root;

        $escaped = false;

        //Transform everything to an AST.
        while ($this->pc < strlen($this->content)) {
            $last->right = $this->toNode();
            $last = $last->right;
        }

        return $root;
    }

    private function toNode()
    {
        $string = "";
        //This is where the logic takes place, transform the string to a node
        while ($this->pc < strlen($this->content)) {
            $word = $this->getNextWord();
            //If $word is a brace (since the getNextWord method returns only one character if it is a brace or escape)
            if ($word == "<{") {
                if ($string != "") {
                    $ast = new DashAST();
                    $ast->type = DashTemplateParser::STRING_TYPE;
                    $ast->string = $string;
                    //Go back, as we have no peak ahead option here
                    $this->pc = $this->pc - 2;
                    return $ast;
                }
                $output = $this->parseKeyword();
                return $output;
            } else {
                //This is a string
                $string .= $word;
            }
        }


        if ($string != "") {
            $ast = new DashAST();
            $ast->type = DashTemplateParser::STRING_TYPE;
            $ast->string = $string;

            $string = "";

            return $ast;
        }
    }

    private function parseKeyword()
    {
        //Key words occur when we get a <{
        $ast = new DashAST();
        $word = $this->getNextWord();
        if (strtolower($word) == "if") {
            //If this is an if statement
            $ast->type = DashTemplateParser::IF_TYPE;
            $word = $this->getNextWord();

            if (strtolower($word) == "not") {
                //A reverse if statement works the same way as an if statement except the output is reversed
                $ast->type = DashTemplateParser::NOT_IF_TYPE;
                $word = $this->getNextWord();
            }

            $ast->string = $word;

            //Store this variable name
            array_push($this->var_list, $word);

            //The if statement stores it's own AST in the left
            $left_root = new DashAST();
            $last = $left_root;
            $word = $this->getNextWord();


            if ($word == "}>") {
                //Close this statement/keyword
                while ($this->pc < strlen($this->content)) {
                    $node = $this->toNode();
                    if ($node->type == DashTemplateParser::ENDIF_TYPE) {
                        $ast->left = $left_root->right;
                        return $ast;
                    } else {
                        $last->right = $node;
                        $last = $last->right;
                    }
                }
                //Generated it's own AST until it closed and append this to the left node
                $ast->left = $left_root->right;
            } else {
                if ($left_root->right != null) {
                    $ast->left = $left_root->right;
                }
            }
        } elseif (strtolower($word) == "end") {
            $word = $this->getNextWord();
            if (strtolower($word) == "if") {
                $ast = new DashAST();
                $ast->type = DashTemplateParser::ENDIF_TYPE;
                $word = $this->getNextWord();

                if ($word == "}>") {
                    return $ast;
                }
            }
        } else {
            //If no keyword is found, this is a variable
            $ast->type = DashTemplateParser::VAR_TYPE;
            $ast->string = $word;

            //Store this variable name
            array_push($this->var_list, $word);

            $word = $this->getNextWord();
            if ($word == "}>") {
                return $ast;
            }
        }

        return $ast;
    }

    private function getNextCharacter($pc)
    {
        //Gets the next character without incrementing the program counter
        $len = strlen($this->content);
        if ($pc + 1 < $len) {
            return $this->content[$pc + 1];
        }
        return $this->content[$pc];
    }

    private function getNextWord()
    {
        $ch = "";
        $next = "";
        $word = "";

        //We do not want to keep writing to the object, it's faster to create a temporary variable for this
        $pc = $this->pc;
        $len = strlen($this->content);
        //Keep going until the end of the string
        while ($pc < $len) {
            $ch = $this->content[$pc];
            //If $ch is a { or a } or \\ then we consider it a keyword
            if (($ch == "<" && $this->getNextCharacter($pc) == "{") || ($ch == "}" && $this->getNextCharacter($pc) == ">") || $ch == "\\") {
                if ($word != "") {
                    $this->pc = $pc;
                    return $word;
                } else {

                    //Fix to stop including spaces in the parser keywords
                    if ($ch == "<" && $this->getNextCharacter($pc) == "{") {
                        $this->inside_keyword = true;
                        $this->pc = $pc + 2;
                        return "<{";
                    }
                    if ($ch == "}" && $this->getNextCharacter($pc) == ">") {
                        $this->inside_keyword = false;
                        $this->pc = $pc + 2;
                        return "}>";
                    }
                    if($ch == "\\" && ($this->getNextCharacter($pc) == "{" || $this->getNextCharacter($pc) == "}" || $this->getNextCharacter($pc) == "<" || $this->getNextCharacter($pc) == ">")){
                        //Escaped character
                        $ch = $this->getNextCharacter($pc++);
                        $this->pc = $pc;
                        echo $ch;
                        $word = $ch;
                    }
                }
            }
            if ($ch == " ") {
                //We are inside or outside of a quote
                if ($word != "") {
                    if ($this->inside_keyword) {
                        //Eat up all spaces after when inside a keyword
                        while ($this->content[$pc] == " " && $pc < $len) {
                            $pc++;
                        }
                    }
                    $this->pc = $pc;
                    return $word;
                } else {
                    //Since $word is equal to nothing (""), we are looking at a space. If the space
                    //is not in a keyword, we count these spaces as words.
                    //This will create a word that is a set of spaces
                    while ($this->content[$pc] == " " && $pc < $len) {
                        $word .= " ";
                        $pc++;
                    }
                    $this->pc = $pc;
                    return $word;
                }

                //If $word is not nothing (""), then let's give back the word without the spaces.
                //Spaces will be traversed on the next getNextWord call.
                return $word;
            }
            //Add the current character to $word and increment the program counter
            $word .= $ch;
            $pc++;
        }
        $this->pc = $pc;
        return $word;
    }
}

/*
* Just as with other languages, DashBoost will convert a string to an AST. It does not
* compile it any further and applies no optimisations to it, but simply traverses the
* resultant AST.
*/

class DashAST extends DashCoreClass
{
    public $type = 0;
    public $string = "";
    public $left = null;
    public $right = null;
}

//This method can be used to test the parser
/*function TestParser()
{


    $str = <<<CONTENT

This is a test page. Your name is <{First_Name}> <{Last_Name}> and
you are a member of <{IF Member}>'\{<{Member}>\}'<{END IF}><{IF NOT Member}>\{no group\}<{END IF}>.

CONTENT;

    $parser = new DashTemplateParser($str);
    $result = $parser->Parse();

    echo $parser->traverseAST($result, array("First_Name" => "Jamie", "Last_Name" => "Balfour"))."\r\n";
    echo $parser->traverseAST($result, array("First_Name" => "Michael", "Last_Name" => "Wizardly", "Member" => "Nintendo"))."\r\n";
    echo $parser->traverseAST($result, array("First_Name" => "John", "Last_Name" => "Smith", "Member" => "Metroid"))."\r\n";
}

TestParser();*/
