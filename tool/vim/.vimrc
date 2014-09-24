:au BufReadPost * if line("'\"") > 0 && line("'\"") <= line("$") | exe "normal g'\"" | endif
syntax on
set enc=utf-8
set laststatus=2
set tags=/www/test3/tags;
set autochdir
set tabstop=4
set softtabstop=4
set shiftwidth=4
set autoindent
set smartindent
set wrap
"禁止生成临时文件
set nobackup
set noswapfile
"set cindent
set nu
set hlsearch
"行号
hi linenr ctermfg=darkgray
hi cursor ctermfg=blue
hi title guifg=gray
hi normal guifg=gray
"注释
hi comment ctermfg=darkgray 
hi keyword ctermfg=red
"字符串
hi string ctermfg=darkgray
"函数
hi function ctermfg=cyan
"预处理
hi todo ctermfg=gray
"系统常量, __FILE__...
hi constant ctermfg=cyan
"特殊字符
hi special ctermfg=white
"echo...
hi preproc ctermfg=cyan
"条件表达式
hi statement ctermfg=magenta
"php的self、->
hi type ctermfg=magenta
"状态栏
hi StatusLine	guifg=darkblue guibg=darkgray gui=none		ctermfg=darkblue ctermbg=black term=none cterm=none
"colorscheme darkblue

"php函数提示
au FileType php call PHPFuncList()
function PHPFuncList()
set dictionary-=~/.vim/php_funclist.txt
set dictionary+=~/.vim/php_funclist.txt
set complete-=k complete+=k
endfunction

"代码提示背景
hi Pmenu ctermfg=darkgreen ctermbg=black

"目录树
nnoremap <silent> <F5> :NERDTree<CR>

"ctags
set tags+=/study/php-src/tags

autocmd FileType python set omnifunc=pythoncomplete#Complete
autocmd FileType javascript set omnifunc=javascriptcomplete#CompleteJS
autocmd FileType html set omnifunc=htmlcomplete#CompleteTags
autocmd FileType css set omnifunc=csscomplete#CompleteCSS
autocmd FileType xml set omnifunc=xmlcomplete#CompleteTags
autocmd FileType php set omnifunc=phpcomplete#CompletePHP
autocmd FileType c set omnifunc=ccomplete#Complete
