<?php

include_once($board_skin_path . '/lib/skin.lib.php');
$option = $hidden = array();
if($is_notice) $option[] = "<input type=\"checkbox\" id=\"notice\" name=\"notice\" value=\"1\" {$notice_checked} /><label for=\"notice\">공지글로 등록합니다</label>";
if($is_secret)
{
	if($is_admin || $is_secret == 1) $option[] = "<input type=\"checkbox\" id=\"secret\" name=\"secret\" value=\"secret\" {$secret_checked} /><label for=\"secret\">비밀글로 작성합니다</label>";
	else $hidden[] = "<input type=\"hidden\" name=\"secret\" value=\"secret\" />";
}
if($is_html)
{
	if($is_dhtml_editor) $hidden[] = "<input type=\"hidden\" name=\"html\" value=\"html1\" />";
	else $option[] = "<input type=\"checkbox\" id=\"html\" name=\"html\" value=\"{$html_value}\" {$html_checked} onclick=\"auto_br(this);\" /><label for=\"html\">HTML로 작성합니다</label>";
}
if($is_mail) $option[] = "<input type=\"checkbox\" id=\"mail\" name=\"mail\" value=\"mail\" {$recv_email_checked} /><label for=\"notice\">답변메일을 수신합니다</label>";
if($is_dhtml_editor)
{
	include_once($g4['path'] . '/lib/cheditor4.lib.php');
	echo "<script type=\"text/javascript\" src=\"{$g4['cheditor4_path']}/cheditor.js\"></script>\n";
	echo cheditor1('wr_content', '100%', '250');
}

if(!$write[wr_1]){
	$write[wr_1] = $write[wr_3] = date("Y.m.d");
}else{
	$write[wr_1] = substr($write[wr_1],0,4).".".substr($write[wr_1],4,2).".".substr($write[wr_1],6,2);
	$write[wr_3] = substr($write[wr_3],0,4).".".substr($write[wr_3],4,2).".".substr($write[wr_3],6,2);
}

$room_arr = array(1=>"대회의실", "소회의실(1)", "소회의실(2)","소회의실(3)");

?>
<script languege="javascript" src="<?=$g4[path]?>/js/popupcalendar.js"></script>
<script type="text/javascript">
var md5_norobot_key = "";
var char_min = <?php echo (int)$write_min; ?>;
var char_max = <?php echo (int)$write_max; ?>;
var g4_is_dhtml_editor = "<?php if($is_dhtml_editor) echo 'wr_content'; ?>";
var g4_is_category = "<?php if($is_category) echo 'true'; ?>";
</script>
<link rel="stylesheet" type="text/css" href="<?php echo $board_skin_path; ?>/css/write.skin.css" />

<center id="G4_WRITE" style="width:<?php echo $width; ?>;margin-left:16px;margin-top:10px;">
	<form method="post" action="<?php if($g4['https_url']) echo "{$g4['https_url']}/{$g4['bbs']}/"; ?>write_update.php" enctype="multipart/form-data" onSubmit="return check_write(this);">
		<p>
			<input type="hidden" name="null" />
			<input type="hidden" name="w" value="<?php echo $w; ?>" />
			<input type="hidden" name="bo_table" value="<?php echo $bo_table; ?>" />
			<input type="hidden" name="wr_id" value="<?php echo $wr_id; ?>" />
			<input type="hidden" name="sca" value="<?php echo $sca; ?>" />
			<input type="hidden" name="sfl" value="<?php echo $sfl; ?>" />
			<input type="hidden" name="stx" value="<?php echo $stx; ?>" />
			<input type="hidden" name="spt" value="<?php echo $spt; ?>" />
			<input type="hidden" name="sst" value="<?php echo $sst; ?>" />
			<input type="hidden" name="sod" value="<?php echo $sod; ?>" />
			<input type="hidden" name="page" value="<?php echo $page; ?>" />
			<?php echo implode("\n", $hidden); ?>
		</p>
		<p class="subject">예약자 등록하기</p>
		<table class="write">
			<?php if($is_name){ ?>
			<tr>
				<td class="title">이름 <img src="<?php echo $board_skin_path; ?>/img/i_required.gif" alt="" /></td>
				<td class="field"><input type="text" name="wr_name" value="<?php echo $name; ?>" size="25" itemname="이름" required="required" maxlength="20" /></td>
			</tr>
			<?php } ?>
			<?php if($is_password){ ?>
			<tr>
				<td class="title">비밀번호 <?php if($w != 'u') echo "<img src=\"{$board_skin_path}/img/i_required.gif\" alt=\"\" />"; ?></td>
				<td class="field"><input type="password" name="wr_password" size="25" itemname="비밀번호" <?php echo $password_required; ?> maxlength="20" /></td>
			</tr>
			<?php } ?>
			<?php if($is_email){ ?>
			<tr>
				<td class="title">이메일</td>
				<td class="field"><input type="text" name="wr_email" value="<?php echo $email; ?>" size="50" itemname="이메일" email="email" maxlength="100" /></td>
			</tr>
			<?php } ?>
			<?php if($is_homepage){ ?>
			<tr>
				<td class="title">홈페이지</td>
				<td class="field"><input type="text" name="wr_homepage" value="<?php echo $homepage; ?>" size="50" /></td>
			</tr>
			<?php } ?>
			<?php /*if(count($option) > 0){ ?>
			<tr>
				<td class="title">옵션</td>
				<td class="field"><?php echo implode(' &nbsp; ', $option); ?></td>
			</tr>
			<?php } */?>
			<?php if($is_category){ ?>
			<tr>
				<td class="title">회의실 <img src="<?php echo $board_skin_path; ?>/img/i_required.gif" alt="" /></td>
				<td class="field"><select id="ca_name" name="ca_name" itemname="분류" required="required"><option value="">선택하세요</option><?php echo selected(explode('|', $board['bo_category_list']), $room_arr[$_GET[room]]); ?></select></td>
			</tr>
			<?php } ?>
			<tr>
				<td class="title">예정기간</p>(기본 2시간) <img src="<?php echo $board_skin_path; ?>/img/i_required.gif" alt="" /></td>
				<td class="field">
					<input type="text" name="wr_1" id="wr_1" value="<?=$write[wr_1]?>" size="15" onclick="popUpCalendar(this, wr_1, 'yyyy.mm.dd')" readonly>
					<select id="wr_2" name="wr_2" itemname="시작시간" required="required"><option value="">시간선택</option>
					<?
						for($i = 54; $i < 127; $i++){
							if(floor($i/6) < 10) {	$hour = "0".floor($i/6); } else { $hour = floor($i/6); }
							if($i%6 != 0) { $min = ($i%6) * 10; } else { $min = "00"; }
							$time = $hour.":".$min;
					?>
						<option value="<?=$time?>" <?if($write[wr_2]==$time) echo " selected"?>><?=$time?></option>
					<?
						}
					?>
					</select>
					&nbsp;~&nbsp;
					<input type="text" name="wr_3" id="wr_3" value="<?=$write[wr_3]?>" size="15" onclick="popUpCalendar(this, wr_3, 'yyyy.mm.dd')" readonly>
					<select id="wr_4" name="wr_4" itemname="종료시간" required="required"><option value="">시간선택</option>
					<?
						for($i = 66; $i < 139; $i++){
							if(floor($i/6) < 10) {	$hour = "0".floor($i/6); } else { $hour = floor($i/6); }
							if($i%6 != 0) { $min = ($i%6) * 10; } else { $min = "00"; }
							$time = $hour.":".$min;
					?>
						<option value="<?=$time?>" <?if($write[wr_4]==$time) echo " selected"?>><?=$time?></option>
					<?
						}
					?>
					</select>					
				</td>
			</tr>
			<tr>
                <td class="title">예약자명<img src="<?php echo $board_skin_path; ?>/img/i_required.gif" alt="" /></td>
				<td class="field"><input type="text" id="wr_subject" name="wr_subject" value="" itemname="제목"></td>
			</tr>
			<tr>
				<td class="title">회의목적 <img src="<?php echo $board_skin_path; ?>/img/i_required.gif" alt="" /></td>
				<td class="field">
					<?php if($bo_table=="conference"){?>
						<input type="text" id="wr_content" name="wr_content" size=50 itemname="내용" required = "required" />
					<?php } else {?>
					<?php if($is_dhtml_editor){echo cheditor2('wr_content', $content);} else{ ?>
					<p class="float">
						<img src="<?php echo $board_skin_path; ?>/img/b_up.gif" alt="늘리기" onclick="textarea_decrease('wr_content', 10);" />
						<img src="<?php echo $board_skin_path; ?>/img/b_iniset.gif" alt="원래대로" onclick="textarea_original('wr_content', 10);" />
						<img src="<?php echo $board_skin_path; ?>/img/b_down.gif" alt="줄이기" onclick="textarea_increase('wr_content', 10);" />
					</p>
					<?php if($write_min || $write_max) echo '<p class="right"><strong id="char_count"></strong>&nbsp;글자</p>'; ?>
					<p class="clear"><textarea id="wr_content" name="wr_content" cols="100" rows="10" itemname="내용" required ="required" <?php if($write_min || $write_max) echo "onkeyup=\"check_byte('wr_content', 'char_count');\""; ?>><?php echo $content; ?></textarea></p>
					<?php if($write_min || $write_max) echo "<script type=\"text/javascript\">check_byte('wr_content', 'char_count');</script>"; ?>
					<?php } }?>
				</td>
			</tr>
			<?php /*if($is_link){ ?>
			<tr>
				<td class="title">링크</td>
				<td class="field"><?php for($i = 1; $i <= $g4['link_count']; $i++) echo "<p><input type=\"text\" name=\"wr_link{$i}\" value=\"". $write["wr_link{$i}"] . "\" size=\"75\" /></p>"; ?></td>
			</tr>
			<?php } ?>
			<?php if($is_file){ ?>
			<tr>
				<td class="title">첨부파일 <img src="<?=$board_skin_path?>/img/b_plus.gif" alt="+" onclick="add_file();" class="cursor" /><img src="<?=$board_skin_path?>/img/b_minus.gif" alt="-" onclick="del_file();" class="cursor" /></td>
				<td class="field">
					<table id="variableFiles"></table>
					<script type="text/javascript">
					var flen = 0;
					function add_file(delete_code)
					{
						var upload_count = <?php echo (int)$board['bo_upload_count']; ?>;
						if(upload_count && flen >= upload_count)
						{
							window.alert("첨부파일은 " + upload_count + "개까지 가능합니다.");
							return;
						}
						var objTbl, objRow, objCell;
						if(window.document.getElementById) objTbl = window.document.getElementById("variableFiles");
						else objTbl = window.document.all["variableFiles"];
						objRow = objTbl.insertRow(objTbl.rows.length);
						objCell = objRow.insertCell(0);
						objCell.innerHTML = "<p><input type=\"file\" name=\"bf_file[]\" class=\"file\" /></p>";
						if(delete_code) objCell.innerHTML += delete_code;
						else
						{
							<?php if($is_file_content){ ?>objCell.innerHTML += "<p><input type=\"text\" name=\"bf_content[]\" size=\"75\" /></p>"<?php } ?>;
						}
						flen++;
						return;
					}
					<?php echo $file_script; ?>
					function del_file()
					{
						var file_length = <?php echo (int)$file_length; ?>;
						var objTbl = window.document.getElementById("variableFiles");
						if(objTbl.rows.length - 1 > file_length)
						{
							objTbl.deleteRow(objTbl.rows.length - 1);
							flen--;
						}
						return;
					}
					</script>
					<p class="explain">[안내] 첨부파일의 용량은 <strong>최대 <?php echo $upload_max_filesize; ?></strong>까지 가능합니다</p>
				</td>
			</tr>
			<?php } ?>
			<?php if($is_trackback){ ?>
			<tr>
				<td class="title">트랙백주소</td>
				<td class="field">
					<input type="text" name="wr_trackback" value="<?php echo $trackback; ?>" size="75" />
					<?php if($w == 'u') echo "<input type=\"checkbox\" id=\"re_trackback\" name=\"re_trackback\" value=\"1\"><label for=\"re_trackback\">재전송</label>"; ?>
				</td>
			</tr>
			<?php } ?>
			<?php if($is_guest){ ?>
			<tr>
				<td class="title">스팸방지 <img src="<?php echo $board_skin_path; ?>/img/i_required.gif" alt="" /></td>
				<td class="field">
					<p class="float"><img id="kcaptcha_image" src="<?php echo $board_skin_path; ?>/img/p_kcaptcha.gif" onclick="imageClick();" alt="" /></p>
					<div class="kcaptcha">
						<p><input type="text" name="wr_key" size="25" itemname="스팸방지" required="required" /></p>
						<p class="explain">[안내] 혹시 글자가 잘 안보이세요? 글자를 클릭하면 <strong>다른 글자가 출력</strong>됩니다.</p>
					</div>
					<p class="clear"></p>
				</td>
			</tr>
			<?php } */?>
		</table>
		<p id="save" class="button">
			<input type="image" src="<?php echo $board_skin_path; ?>/img/b_save.gif" alt="저장하기" />&nbsp;&nbsp;
			<a href="javascript:parent.dialog.hide();"><img src="<?php echo $board_skin_path; ?>/img/b_cancel.gif" alt="취소하기" /></a>
		</p>

	</form>
</center>
<script type="text/javascript" src="<?php echo $g4['path']; ?>/js/prototype.js" charset="<?php echo $g4['charset']; ?>"></script>
<script type="text/javascript" src="<?php echo $board_skin_path; ?>/js/write.skin.js" charset="<?php echo $g4['charset']; ?>"></script>
<script type="text/javascript" src="<?php echo $g4['path']; ?>/js/board.js" charset="<?php echo $g4['charset']; ?>"></script>